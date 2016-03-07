<?php
defined('DS_ENGINE') or die('web_demon laughs');

class column_addition
{
    private $id;
    private $note;
    private $favorite;
    private $hide;
    public $category;

    public function __construct($data)
    {
        
        $this->id = $data[0];
        $this->favorite = $data[1];
        $this->note = $data[2];
        $this->category = $data[3];
        $this->hide = $data[4];
       
    }

    public function before_load()
    {
        return array(
            'sortcolumn' => ' `ds_maindata`.`id` ' //'sortcolumn' => ' `ds_maindata_favorive`.`item` '
        );
    }

    public function name()
    {
        return array(
            'name' => '<i title="Дополнительно" class="">#</i>', //А, че, оказывается можно и иконку засунуть
            'style' => 'max-width: 50px;min-width: 40px;',
            'nosort' => 1
        );
    }

    public function process()
    {
        if($this->favorite)
            $favStar = '<i title="Удалить лот из избранного" class="icon-star-clicked"></i>';
        else
            $favStar = '<i title="Добавить лот в избранное" class="icon-star-empty"></i>';
        
        if( $this->hide )
            $hideStar = '<i title="Удалить лот из скрытых" class="icon-graystar-clicked"></i>';
        else
            $hideStar = '<i title="Добавить лот в скрытые" class="icon-star-empty"></i>';
        
        if($this->note)
            $note = '<i title="Комментарий к лоту" class="fa fa-sticky-note"></i>';
        elseif($this->category == '-1' && $this->favorite)
            $note = '<i title="Комментарий к лоту" class="fa fa-sticky-note-o"></i>';
        else 
            $note = '';
        
        $coldata = [
            '<span class="icon_to_click_fav" attr="'.$this->id.'">'.$favStar.'</span>',
            '<span class="icon_to_click_hide" hide_attr="'.$this->id.'">'.$hideStar.'</span>',
            '<span data-note="'.$this->note.'" data-id="'.$this->id.'" id="note">'.$note.'</span>'
        ];
        
        return [
            'col' => implode('<br/>', $coldata),
            'style' => 'text-align:center;'
        ];
    }
}
