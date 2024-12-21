<?php

namespace Core\Menu;

use Core\crud\Select;
use Core\Helpers\mysqlClause\OrderBy;
use Core\Helpers\mysqlClause\Where;

class AdminPanelMenu extends Select
{
    protected $table = 'adminPanelUrl';
    use Where, OrderBy;

    public function panelMenuBuilder($parentId = 0): string
    {
        $result = $this->select()->where(['parentId', $parentId])->orderBy('sort')->getResult();
        $this->generatePanelMenuLinks($result, $output);
        return $output;
    }

    private function generatePanelMenuLinks(array $queryResult, &$output): void
    {
        $output = '<ul>';
        foreach ($queryResult as $row) {
            $output .= ($row->dropdown) ?
                '<i class="caretDown"></i><li><label for="link' . $row->urlId . '" >' . $row->persianUrl . '</label>
                <input type="checkbox" name="link" id="link' . $row->urlId . '">' . $this->panelMenuBuilder($row->urlId) . '</li>'
                :
                '<li><a href="' . $row->url . '">' . $row->persianUrl . '</a></li>';
        }
        $output .= '</ul > ';
    }
}
