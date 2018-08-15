<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class CompassesController extends Controller
{
    public function index(Request $request)
    {
        $active_tab = null;

        $commands = $this->getArtisanCommands();

        $artisanOutput = '';

        return view('admin.compasses.index', compact(
            'active_tab',
            'commands',
            'artisanOutput'
        ));
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
