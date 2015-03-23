<?php
require_once('deploy.config.php');

function logIt($text, $save = false) {
    $msg = date('d.m.Y, H:i:s', time()) .': '.$text."\r\n";
    if(!$save) return $msg;
    $logfile = fopen('logs/deploy.txt', 'a');
    fputs($logfile, $msg);
    fclose($logfile);
}

function upload_payload($payload) {
    global $bitbucket_username, $bitbucket_password;

    $json = stripslashes($payload);
    $data = json_decode($json);
    $slug = $data->repository->slug;
    $uri = $data->repository->absolute_url;
    
    $project = get_project($slug);

    $update_branch = '';
    foreach ($data->commits as $commit) {
        if ($commit->branch)
          $update_branch = $commit->branch;
    }

    $commit_data = array();
    foreach($data->commits as $commit) {
        $branch = $commit->branch;
        if (!array_key_exists($branch, $project['branches'])) {
            if(!$branch)
                $branch = $update_branch;
            else
                continue;
        }
        if (!array_key_exists($branch, $commit_data)) {
            $commit_data[$branch] = array();
        }
        
        $node = $commit->node;
        foreach($commit->files as $file) {
            $commit_data[$branch][] = array($node, $file);
        }
    }
    
    $logMsg = logIt('Receive data by '.$slug);

    foreach($commit_data as $branch => $data) {
        list($ftp_host, $ftp_user, $ftp_pass, $ftp_path) = get_branch_ftp_data($project, $branch);
        $logMsg .= logIt('Connecting to branch '.$branch.' at '.$ftp_host.'/'.$ftp_user);
        if (substr($ftp_path, 0, 1) != '/') $ftp_path = '/' . $ftp_path;
        if (substr($ftp_path, strlen($ftp_path) - 1, 1) != '/') $ftp_path = $ftp_path . '/';
        $conn_id = ftp_connect($ftp_host);
        ftp_login($conn_id, $ftp_user, $ftp_pass);
        foreach($data as $step) {
            list($node, $file) = $step;
            $path = $file->file;
            $logMsg .= logIt($path . ' is '. $file->type);
            if ($file->type=="removed") {
                ftp_delete($conn_id, $ftp_path.$path);
                $logMsg .= logIt('Removed '.$ftp_path.$path);
            } else {
                $url = 'https://api.bitbucket.org/1.0/repositories'.$uri.'raw/'.$node.'/'.$file->file;
                $dirname = dirname($path);
                $chdir = @ftp_chdir($conn_id, $ftp_path . $dirname);
                if ($chdir == false){
                    if (make_directory($conn_id, $ftp_path.$dirname)){
                        $logMsg .= logIt('Created new directory '.$dirname);
                    } else {
                        $logMsg .= logIt('Error: failed to create new directory '.$dirname);
                    }
                }
                
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_USERPWD, $bitbucket_username . ':' . $bitbucket_password);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
                $data = curl_exec($ch);
                curl_close($ch);

                $temp = tmpfile();
                fwrite($temp, $data);
                fseek($temp, 0);
                ftp_fput($conn_id, $ftp_path.$path, $temp, FTP_BINARY);
                fclose($temp);

                $logMsg .= logIt('Uploaded: '.$ftp_path.$path);
            }
        }
        ftp_close($conn_id);
    }
    logIt($logMsg, true);
}

function get_branch_ftp_data($project, $wanted_branch) {
    foreach($project['branches'] as $branch => $data) {
        if (strtolower($branch) == strtolower($wanted_branch)) {
            return array($data['ftp_host'], $data['ftp_user'], $data['ftp_pass'], $data['ftp_path']);
        }
    }
    logIt('error: did not find ftp data for project="'.$project['slug'].'" for branch="'.$wanted_branch.'"', true);
    exit();
}

function get_project($git_slug) {
    global $projects;
    foreach ($projects as $proj) {
        if (strtolower($proj['git_slug']) == strtolower($git_slug)) 
            return $proj;
    }
    logIt('error: get_project found nothing for git_slug="'.$git_slug.'"', true);
    exit();
}

function make_directory($ftp_stream, $dir) {
	if ($dir == dirname($dir)) return true;
    if (ftp_is_dir($ftp_stream, $dir) || @ftp_mkdir($ftp_stream, $dir)) return true;
    if (!make_directory($ftp_stream, dirname($dir))) return false;
    return ftp_mkdir($ftp_stream, $dir);
}

function ftp_is_dir($ftp_stream, $dir){
    $original_directory = ftp_pwd($ftp_stream);
    if ( @ftp_chdir($ftp_stream, $dir) ) {
        ftp_chdir( $ftp_stream, $original_directory );
        return true;
    }
    return false;
}

file_put_contents('logs/post.txt', serialize($_POST['payload']));

if (isset($_POST['payload']))
    upload_payload($_POST['payload']);

?>
Здесь вам не тут!