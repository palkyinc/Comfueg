<?php

declare(strict_types = 1);

namespace App\Charts;

use Chartisan\PHP\Chartisan;
use ConsoleTVs\Charts\BaseChart;
use Illuminate\Http\Request;
use App\Models\Issue_title;

class TortaIssuesChart extends BaseChart
{
    /**
     * Determines the chart name to be used on the
     * route. If null, the name will be a snake_case
     * version of the class name.
     */
    public ?string $name = 'tortaIssues_name';

    /**
     * Determines the name suffix of the chart route.
     * This will also be used to get the chart URL
     * from the blade directrive. If null, the chart
     * name will be used.
     */
    public ?string $routeName = 'tortaIssues';

/**
     * Handles the HTTP request for the given chart.
     * It must always return an instance of Chartisan
     * and never a string or an array.
     */
    public function handler(Request $request): Chartisan
    {
        $datos = (json_decode(htmlspecialchars_decode($request->header('datos'))));
        $dataset = $labels = [];
        foreach ($datos as $key => $value) {
            $labels [] = Issue_title::find($key)->title . ': ' . $value;
            $dataset [] = $value;
        }
        return Chartisan::build()
            ->labels($labels)
            ->dataset('Título', $dataset);
    }
}