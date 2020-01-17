<?php

class GoogleChartsPDFPrinter {

   const CHART_DPI = 800;
   const CHART_SMALL = 1;
   const CHART_BIG = 2;
   const CHART_HUGE = 3;
   const CHART_SMALL_MAX_WIDTH = 100;
   const CHART_BIG_MAX_WIDTH = 200;
   const CHART_HUGE_MAX_WIDTH = 290;

   protected $pdf;
   protected $charts_data;
   protected $before_width;

   public function __construct() {
      $this->pdf = null;
      $this->before_width = 0;
      $this->charts_data = array();
   }

   public function init($pdf, $charts_data) {
      $this->pdf = $pdf;
      $this->charts_data = $charts_data;
      return $this;
   }

   public function printChartsToPDF() {
      $log = LoggerManager::getLogger();
      if ( !$this->pdf ) {
         $log->fatal(basename(__FILE__) . ':' . __LINE__ . ':' . __FUNCTION__ . ': ' . ' GoogleChartsPDFPrinter not initialized');
         return false;
      }
      if ( !count($this->charts_data) ) {
         $log->warn(basename(__FILE__) . ':' . __LINE__ . ':' . __FUNCTION__ . ': ' . ' GoogleChartsPDFPrinter charts data not initialized');
      }

      $last_chart_index = count($this->charts_data) - 1;

      foreach ( $this->charts_data as $index => $chart_data ) {
         $chart_img = explode(',', $chart_data->chart)[1];
         $chart_size = $this->getChartSize($chart_data->width);
         $chart_calculated_width = $this->calculateChartWidth($chart_size);

         $next_align = 'N';
         if ( $index < $last_chart_index ) {
            $next_chart_size = $this->getChartSize($this->charts_data[$index + 1]->width);
            $next_chart_width = $this->calculateChartWidth($next_chart_size);
            $page_size = round($this->pdf->getPageWidth());
            if ( $next_chart_size <= self::CHART_BIG && $next_chart_width <= (($page_size - 20) - ($this->before_width + $chart_calculated_width)) ) {
               $next_align = 'T';
            }
         }

         $this->pdf->Image('@' . base64_decode($chart_img), $this->before_width, '', $chart_calculated_width, 0, "PNG", '', $next_align, true, self::CHART_DPI);

         if ( $next_align == 'N' ) {
            $this->before_width = 0;
         } else {
            $this->before_width += $chart_calculated_width;
         }
      }
      $this->writeHTMLSeparator();
   }

   protected function writeHTMLSeparator() {
      $this->pdf->writeHTMLCell(0, 12, '', '', '', 0, 1);
   }

   protected function getChartSize($chart_width) {
      $chart_size = self::CHART_SMALL;
      if ( $chart_width > 550 ) {
         $chart_size = self::CHART_BIG;
         if ( $chart_width > 1500 ) {
            $chart_size = self::CHART_HUGE;
         }
      }
      return $chart_size;
   }

   protected function calculateChartWidth($chart_size) {
      $page_size = $this->pdf->getPageWidth();
      switch ( $chart_size ) {
         case self::CHART_SMALL:
            $chart_calculated_width = ($page_size - 30) / 2;
            if ( $chart_calculated_width > self::CHART_SMALL_MAX_WIDTH ) {
               $chart_calculated_width = self::CHART_SMALL_MAX_WIDTH;
            }
            break;
         case self::CHART_BIG:
            $chart_calculated_width = ($page_size - 30);
            if ( $chart_calculated_width > self::CHART_BIG_MAX_WIDTH ) {
               $chart_calculated_width = self::CHART_BIG_MAX_WIDTH;
            }
            break;
         case self::CHART_HUGE:
            $chart_calculated_width = ($page_size - 30);
            if ( $chart_calculated_width > self::CHART_HUGE_MAX_WIDTH ) {
               $chart_calculated_width = self::CHART_HUGE_MAX_WIDTH;
            }
            break;
         default:
            $chart_calculated_width = ($page_size - 30);
      }
      return $chart_calculated_width;
   }

}
