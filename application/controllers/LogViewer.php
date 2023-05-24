<?php

class LogViewer extends CI_Controller {
    public function index() {   
        $data['title'] = 'Error Log';

        $data['clear'] = site_url('tool/error_log/clear');

        $data['log'] = '';

         // Current Filename;
        $file = FCPATH . 'application/logs/' . 'log-'.date('Y-m-d').'.php';

        if (file_exists($file)) {
            $size = filesize($file);

            if ($size >= 5242880) {
                $suffix = array(
                    'B',
                    'KB',
                    'MB',
                    'GB',
                    'TB',
                    'PB',
                    'EB',
                    'ZB',
                    'YB'
                );

                $i = 0;

                while (($size / 1024) > 1) {
                    $size = $size / 1024;
                    $i++;
                }

                $error_warning = 'Warning: Your error log file %s is %s!';

                $data['error_warning'] = sprintf($error_warning, basename($file), round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i]);
            } else {

                 // Updated from comment

                $log = file_get_contents($file, FILE_USE_INCLUDE_PATH, null); 
                $lines = explode("\n", $log); 
                $content = implode("\n", array_slice($lines, 1)); 
                $data['log'] = $content;
            }
        }
        
        $data['page_name'] = 'error_log';
        $data['page_title'] = 'error_log';
        $this->load->view('backend/index', $data);
    }
}