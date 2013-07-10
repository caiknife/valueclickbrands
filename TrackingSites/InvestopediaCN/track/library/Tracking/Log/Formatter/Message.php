<?php

class Tracking_Log_Formatter_Message extends Mezi_Log_Formatter_Message
{
    /**
     * format message
     *
     * @param string $priorityName
     * @param array $params
     * @return string
     */
    public function formatMessage($priorityName, $params, $separator = '|#')
    {
        $output = parent::formatMessage($priorityName, $params);

        $s = addcslashes($separator, '\0..\31$^.*()|\\/+\'\"');
        $pattern = '/(<\w+>'.$s.'|'.$s.'<\w+>)/i';
        $output = preg_replace($pattern, $separator, $output);

        return $output;
    }
}