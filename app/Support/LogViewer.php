<?php

namespace App\Support;

class LogViewer
{
    private const MAX_FILE_SIZE = 52428800;

    private static $file;

    /**
     * @var array
     */
    private static $levelClasses = [
        'debug' => 'info',
        'info' => 'info',
        'notice' => 'info',
        'warning' => 'warning',
        'error' => 'danger',
        'critical' => 'danger',
        'alert' => 'danger',
        'emergency' => 'danger',
        'processed' => 'info',
    ];

    /**
     * @var array
     */
    private static $levelImgs = [
        'debug' => 'info',
        'info' => 'info',
        'notice' => 'info',
        'warning' => 'warning',
        'error' => 'warning',
        'critical' => 'warning',
        'alert' => 'warning',
        'emergency' => 'warning',
        'processed' => 'info',
    ];

    /**
     * @var array
     */
    private static $logLevels = [
        'emergency',
        'alert',
        'critical',
        'error',
        'warning',
        'notice',
        'info',
        'debug',
        'processed',
    ];

    public static function setFile($file)
    {
        $file = self::pathToLogFile($file);

        if (app('files')->exists($file)) {
            self::$file = $file;
        }
    }

    /**
     * @param string $file
     *
     * @return string
     *
     * @throws \Exception
     */
    public static function pathToLogFile($file)
    {
        $logsPath = storage_path('logs');

        if (app('files')->exists($file)) {
            return $file;
        }

        $file = $logsPath . '/' . $file;

        if (\dirname($file) !== $logsPath) {
            throw new \Exception('No such log file.');
        }

        return $file;
    }

    public static function getFileName()
    {
        return basename(self::$file);
    }

    public static function all()
    {
        $logs = [];

        $pattern = '/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}\].*/';

        if (! self::$file) {
            $files = self::getFiles();

            if (! \count($files)) {
                return [];
            }
            self::$file = $files[0];
        }

        if (app('files')->size(self::$file) > self::MAX_FILE_SIZE) {
            return [];
        }

        $file = app('files')->get(self::$file);

        preg_match_all($pattern, $file, $headings);
        if (! \is_array($headings)) {
            return $logs;
        }

        $data = preg_split($pattern, $file);

        if ($data[0] < 1) {
            array_shift($data);
        }

        foreach ($headings as $h) {
            for ($i = 0, $j = \count($h); $i < $j; $i++) {
                foreach (self::$logLevels as $level) {
                    if (stripos(strtolower($h[$i]), '.' . $level) || stripos(strtolower($h[$i]), $level . ':')) {
                        preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\](?:.*?(\w+)\.|.*?)' . $level . ': (.*?)( in .*?:[0-9]+)?$/i', $h[$i], $current);
                        if (! isset($current[3])) {
                            continue;
                        }

                        $logs[] = [
                            'context' => $current[2],
                            'level' => $level,
                            'level_class' => self::$levelClasses[$level],
                            'level_img' => self::$levelImgs[$level],
                            'date' => $current[1],
                            'text' => $current[3],
                            'in_file' => $current[4] ?? null,
                            'stack' => preg_replace("/^\n*/", '', $data[$i]),
                        ];
                    }
                }
            }
        }

        return array_reverse($logs);
    }

    public static function getFiles($basename = false)
    {
        $files = glob(storage_path() . '/logs/*.log');
        $files = array_reverse($files);
        $files = array_filter($files, 'is_file');
        if ($basename && \is_array($files)) {
            foreach ($files as $k => $file) {
                $files[$k] = basename($file);
            }
        }

        return array_values($files);
    }
}
