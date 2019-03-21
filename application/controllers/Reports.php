<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;


/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Reports extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->isLoggedIn();   
    	$this->load->model('respondent_model','respondent');
            $this->load->model('post_model','posts');
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $listpost = $this->posts->getlist();  
        $this->global['listpost'] = $listpost;
        $this->global['pageTitle'] = 'Reports';
        
        $this->global['cities'] = $this->messages->cities();
        
        $this->loadViews("reports/excel", $this->global, NULL , NULL);
    }
    public function excel()
    {
        $listpost = $this->posts->getlist();  
        $this->global['listpost'] = $listpost;
        $this->global['pageTitle'] = 'Reports';

        $this->global['cities'] = $this->messages->cities();
        
        $this->loadViews("reports/excel", $this->global, NULL , NULL);
    }
    public function generate($value='')
    {
    	$obj = (object)$this->input->post();
		

    	$basic = json_decode($obj->basic);
    	$farm = json_decode($obj->farm);
    	$production = json_decode($obj->production);
    	$pest = json_decode($obj->pests);
    	$postharvest = json_decode($obj->postharvest);
    	$marketing = json_decode($obj->marketing);

    	//$letters = range('A', 'Z');
    	$letters =  $this->messages->excelcolumn('ZZ');

    	$basics = array();
    	$column_first = array();

    	$info = array_merge($basic,$farm,$production,$pest,$postharvest,$marketing);


    	foreach ($info as $value) {
    		# code...
    		if ($value != 'organization' && $value != 'seminar') {
    			# code...
    			if ($value == 'fullname') {
    				# code...
    			$basics[] = " CONCAT(fname,' ',lname) as fullname";

    			}else{

    			$basics[] = $value;
    			}
    			$column_first[] = $value;

    		}

    	}

    	$this->load->model('reports_model','report');

    	$basic_data = $this->report->getfilterdata($basics,$obj->city,$obj->year);

    	if ($basic_data == false) {
    		# code...
    		echo json_encode(array('status'=>false));
    		exit();
    	}

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $i=0;
        $a=1;
        foreach ($column_first as $col) {
        	# code...

        $sheet->setCellValue($letters[$i].$a, $col);
        $sheet->getStyle($letters[$i].$a)->applyFromArray(
			   array(
			   		'fill' => array(
				            'type' => Fill::FILL_SOLID,
          					'color' => array('rgb' => 'E5E4E2' )
				        ),
			      	'font'  => array(
			          'bold'  =>  true
			      )
			   )
			 );

        $i++; 

        }

			$a=2;
        foreach ($basic_data as $key) {
        	# code...

        	$j=0;
        	foreach ($key as  $value) {
        		# code...

			    $sheet->setCellValue($letters[$j].$a, $value);
			    $j++;
        	}
        	$a++;
        }       

        
        $writer = new Xlsx($spreadsheet);
 
        $filename = 'report-'.date('Y-m-d H-i-s').'-'.uniqid().'.xlsx';

         $writer->save('file/'.$filename);
         echo json_encode(array('status'=>true,'output'=>$filename));
		
 		/*to download ouput below code*/
        //$filename = 'name-of-the-generated-file';
        //header('Content-Type: application/vnd.ms-excel');
        //header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); 
        //header('Cache-Control: max-age=0');        
        //$writer->save('php://output'); // download file 



    }
    public function generateexcel($value='')
    {
    	# code...

    }
	public function download($file='')
	{
		# code...

		$this->load->helper('download');
		$file = str_replace(" ", "%20", $file);
		$path    =   file_get_contents(base_url()."file/".$file);
		$newname    =   "report-".date('Y-m-d').".xlsx";
		force_download($newname, $path);

        exit();
	}



///

}