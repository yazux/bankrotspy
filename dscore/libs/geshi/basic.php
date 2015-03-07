<?php
/*************************************************************************************
 * basic.php
 * ---------------------------------
 *
 * Mobile Basic language file for GeSHi.
 *
 ************************************************************************************/

$language_data = array (
    'LANG_NAME' => 'Mobile Basic',
    'COMMENT_SINGLE' => array(1 => 'REM', 2 => 'rem'),
    'COMMENT_MULTI' => array(),
    'CASE_KEYWORDS' => GESHI_CAPS_NO_CHANGE,
    'QUOTEMARKS' => array('"'),
    'ESCAPE_CHAR' => '',
    'KEYWORDS' => array(
        1 => array(

            // Navy Blue Bold Keywords

            'left','right','up','down','fire','fr',
            'gamea','gameb','gamec','gamed'
            ),
        2 => array(

            // Red Lowercase Keywords

            'abs','acos','ag','al','alert','alphagel','asc','asin','atan','blit','cag','call','cf','choiceform','chr$','cl','close','cls','coloralphagel',
            'cos','da','data','dateform','day','days','delgel','delsprite','df','dg','dir','dl','dr','drawarc','drawgel','drawrect','drawroundrect',
            'drawstring','drr','ds','editform','ef','endsub','err','es','exp','fa','fillarc','fillrect','fillroundrect','fre','frr',
            'gaugeform','gelgrab','gelheight','gelload','gelwidth','get','gf','gg','gh','gl','gw','hour','ik','in',
            'inkey','input','iscolor','left$','len','log','ma','menuadd','menuitem','menuremove','messageform','mi','mid$','millisecond',
            'milliseconds','minute','mkdir','mod','month','mr','msf','note','numcolors','op','open','pd','pfr','ph','platformrequest','playtone','playwav',
            'plot','point','pointdragged','pointhold','pointpressed','pointx','pointy','pp','pr','print','property$','pt','put','pw','px','py','rand','re',
            'readdir$','read','drawline','repaint','restore','right$','rnd','sch','sc','screenheight','screenwidth','scw','second','sel',
            'select','sendsms','setcolor','setfont','sf','sg','sh','sin','sl','sleep','sm','spritegel','spritehit','spritemove','sqr','ss','sth','str$',
            'stringheight','stringwidth','stw','tan','tr','trap','val','year'
            ),
        3 => array(

            // Blue Lowercase Keywords

            'and', 'bitand', 'bitor', 'bitxor', 'bye', 'deg', 'del', 'delete', 'dim', 'edit', 'end', 'enter', 'for', 'gosub', 'goto', 'gs', 'gt',
            'if', 'list', 'load', 'new', 'next', 'not', 'or', 'pop', 'rad', 'ret', 'return', 'run', 'save', 'step', 'stop', 'th', 'then', 'to'
            )
        ),
    'SYMBOLS' => array(
        '=', '<', '>', '>=', '<=', '+', '-', '*', '/', '^', '(', ')', '[', ']', '<>', '$', '#', ':'
        ),
    'CASE_SENSITIVE' => array(
        GESHI_COMMENTS => false,
        1 => false,
        2 => false,
        3 => false,
        ),
    'STYLES' => array(
        'KEYWORDS' => array(
            1 => 'color: #A00000;',
            2 => 'color: #FF0000;',
            3 => 'color: #0000FF;'
            ),
        'COMMENTS' => array(
            1 => 'color: #8A8A8A; font-style: italic;'
            ),
        'BRACKETS' => array(
            0 => 'color: #000080;'
            ),
        'STRINGS' => array(
            0 => 'color: #008000;'
            ),
        'NUMBERS' => array(
            0 => 'color: #000080; font-weight: bold;'
            ),
        'METHODS' => array(
            ),
        'SYMBOLS' => array(
            0 => 'color: #0000FF;'
            ),
        'ESCAPE_CHAR' => array(
            ),
        'SCRIPT' => array(
            ),
        'REGEXPS' => array(
            )
        ),
    'URLS' => array(
        1 => '',
        2 => '',
        3 => ''
        ),
    'OOLANG' => false,
    'OBJECT_SPLITTERS' => array(
        ),
    'REGEXPS' => array(
        ),
    'STRICT_MODE_APPLIES' => GESHI_NEVER,
    'SCRIPT_DELIMITERS' => array(
        ),
    'HIGHLIGHT_STRICT_BLOCK' => array(
        ),
    'TAB_WIDTH' => 4
);

?>
