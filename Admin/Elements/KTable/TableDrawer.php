<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 15/6/25
 * Time: 00:51
 */

namespace Xjtuwangke\Admin\Elements\KTable;


use Xjtuwangke\QueryRequests\QueryRequest;

class TableDrawer {

    /**
     * 将KTable实例解析成HTML
     * @param KTable            $table
     * @param QueryRequest $request
     * @return string
     */
    public static function render( KTable $table , QueryRequest $request ){
        $thead = '';
        foreach( $table->getTheads() as $th ){
            $thead.= $th->render( $request );
        }
        $tbody = '';
        foreach( $table->getTbody() as $tr ){
            $tbody.="<tr {$tr->attributes}>";
            foreach( $tr->children as $td ){
                $tbody.= "<td {$td->attributes}>{$td->html}</td>\n";
            }
            $tbody.='</tr>';
        }
        $attributes = $table->getAttributes();
        $tail       = $table->getTail();
        $html = <<<TABLE
<table {$attributes}>
    <thead>
        {$thead}
    </thead>
    <tbody>
        {$tbody}
    </tbody>
</table>
{$tail}
TABLE;
        return static::wrapper( $html );
    }

    /**
     * td中的图片
     * @param $src
     * @return string
     */
    public static function imageInsideTd( $src ){
        return "<img src='{$src}' class='img img-responsive admin-img' style='float:left;'>";
    }

    /**
     * @param      $text
     * @param int  $length
     * @param bool $xss
     * @return string
     */
    public static function textInsideTd( $text , $length = 15 , $xss = true ){
        if( $xss ){
            $text = e( $text );
        }
        if( mb_strlen( $text ) > $length ){
            $full = $text;
            $full = str_replace( "\"" , "&quot" , $full );
            $text = mb_substr( $text , 0 , $length ) . '...';
            $text.= "<span class='glyphicon glyphicon-info-sign' data-toggle='tooltip' data-placement='left' title=\"{$full}\"></span>";
        }
        return $text;
    }

    public static function wrapper( $html ){
        return <<<HTML
<form method="get">
{$html}
</form>
HTML;

    }

}