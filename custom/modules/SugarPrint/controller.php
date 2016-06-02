<?php


class SugarPrintController extends SugarController {
    function action_getdialog() {
        $this->view = 'getdialog';
    }
    function action_getsavedreports() {
        $this->view = 'getsavedreports';
    }
    function action_loadreport() {
        $this->view = 'loadreport';
    }
    function action_getaddfields() {
        $this->view = 'getaddfields';
    }    
    function action_savereport() {
        $this->view = 'savereport';
    }
    function action_reportpdf() {
        $this->view = 'reportpdf';
    }
    function action_slick() {
        $this->view = 'slick';
    }
    
}
?>