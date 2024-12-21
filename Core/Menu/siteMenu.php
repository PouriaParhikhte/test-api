<?php

namespace Core\Menu;

use Core\crud\Select;
use Core\Helpers\mysqlClause\OrderBy;
use Core\Helpers\mysqlClause\Where;

class SiteMenu extends Select
{
    protected $table = 'url';
    use Where, OrderBy;

    public function siteMenuBuilder($parentId = 0): string
    {
        $result = $this->select()->where(['parentId', $parentId])->orderBy('sort')->getResult();
        $this->generateMenuLinks($result, $output);
        return $output;
    }

    private function generateMenuLinks($queryResult, &$output): void
    {
        $output .= '<ul>';
        foreach ($queryResult as $row) {
            $output .= $row->dropdown ? '<li><a href="' . $row->url . '">' . $row->persianUrl . '</a>' .
                $this->siteMenuBuilder($row->urlId) . '</li>' :
                '<li><a href="' . $row->url . '">' . $row->persianUrl . '</a></li>';
        }
        $output .= '</ul>';
    }
}
