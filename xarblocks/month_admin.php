<?php

/**
 * Calendar Module
 *
 * @package modules
 * @subpackage calendar module
 * @category Third Party Xaraya Module
 * @version 1.0.0
 * @copyright (C) copyright-placeholder
 * @license GPL {@link http://www.gnu.org/licenses/gpl.html}
 * @author Marc Lutolf <mfl@netspan.ch>
 */


class Calendar_MonthBlockAdmin extends Calendar_MonthBlock
{
    public function modify()
    {
        $data = $this->getContent();
        return $data;
    }

    public function update($data = [])
    {
        $args = [];
        $this->var()->find('targetmodule', $args['targetmodule'], 'str', $this->targetmodule);
        $this->var()->find('targettype', $args['targettype'], 'str', $this->targettype);
        $this->var()->find('targetfunc', $args['targetfunc'], 'str', $this->targetfunc);

        $this->setContent($args);
        return true;
    }
}
