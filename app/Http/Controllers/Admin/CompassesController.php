<?php

namespace App\Http\Controllers\Admin;

use App\Support\LogViewer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CompassesController extends Controller
{
    /**
     * 首页。
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Contracts\View\Factory|
     *         \Illuminate\View\View|
     *         \Symfony\Component\HttpFoundation\BinaryFileResponse
     *
     * @throws \Exception
     */
    public function index(Request $request)
    {
        $activeTab = '';

        if ($request->input('logs')) {
            $activeTab = 'logs';
            LogViewer::setFile(base64_decode($request->input('logs')));
        }

        if ($download = $request->input('download')) {
            return $this->download(LogViewer::pathToLogFile(base64_decode($download)));
        }

        $commands = $this->getArtisanCommands();
        $artisanOutput = '';

        if ($request->isMethod('POST')) {
            $command = $request->command;
            $args = $request->args;
            $args = $args !== null ? ' ' . $args : '';

            try {
                $process = new Process('cd ' . base_path() . ' && php artisan ' . $command . $args);
                $process->run();

                if (! $process->isSuccessful()) {
                    throw new ProcessFailedException($process);
                }
                $artisanOutput = $process->getOutput();
            } catch (\Exception $e) {
                $artisanOutput = $e->getMessage();
            }
            $activeTab = 'commands';
        }

        $logs = LogViewer::all();
        $files = LogViewer::getFiles(true);
        $filename = LogViewer::getFileName();

        return view('admin.compasses.index', compact(
            'activeTab',
            'logs',
            'files',
            'commands',
            'artisanOutput',
            'filename'
        ));
    }

    private function download($data)
    {
        if (\function_exists('response')) {
            return response()->download($data);
        }

        return app(Response::class)->download($data);
    }

    public function getArtisanCommands()
    {
        Artisan::call('list');

        // Get the output from the previous command.
        $output = Artisan::output();
        $output = $this->filterArtisanOutput($output);

        return $this->getCommandsFromOutput($output);
    }

    private function filterArtisanOutput($output)
    {
        $output = array_filter(explode("\n", $output));
        $index = array_search('Available commands:', $output, true);
        $output = \array_slice($output, $index - 2, \count($output));

        return $output;
    }

    private function getCommandsFromOutput($output)
    {
        $commands = [];

        foreach ($output as $line) {
            if (empty(trim(substr($line, 0, 2)))) {
                $parts = preg_split('/  +/', trim($line));
                $commands[] = (object) ['name' => trim(@$parts[0]), 'description' => trim(@$parts[1])];
            }
        }

        return $commands;
    }
}
