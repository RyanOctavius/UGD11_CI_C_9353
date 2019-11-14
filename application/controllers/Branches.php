<?php



use Restserver\Libraries\REST_Controller;



class Branches extends REST_Controller

{

    public function __construct()

    {

        header('Access-Control-Allow-Origin: *');

        header("Access-Control-Allow-Methods: GET, OPTIONS, POST, DELETE");

        header("Access-Control-Allow-Headers: Content-Type, Content-Length,Accept-Encoding");

        parent::__construct();

        $this->load->model('BranchModel');

        $this->load->library('form_validation');

    }



    public function index_get()

    {

        return $this->returnData($this->db->get('branches')->result(), false);

    }



    public function index_post($id = null)

    {

        $validation = $this->form_validation;

        $rule = $this->BranchModel->rules();

        if ($id == null) {

            array_push(

                $rule,

                [

                    'field' => 'name',

                    'label' => 'name',

                    'rules' => 'required'

                ],

                [

                    'field' => 'address',

                    'label' => 'address',

                    'rules' => 'required'

                ],

                [

                    'field' => 'phoneNumber',

                    'label' => 'phoneNumber',

                    'rules' => 'required'

                ]
            );

        } else {

            array_push(

                $rule,

                [

                    'field' => 'name',

                    'label' => 'name',

                    'rules' => 'required',

                    'field' => 'address',

                    'label' => 'address',

                    'rules' => 'required',

                    'field' => 'phoneNumber',

                    'label' => 'phoneNumber',

                    'rules' => 'required'

                ]

            );

        }

        $validation->set_rules($rule);

        if (!$validation->run()) {

            return $this->returnData($this->form_validation->error_array(), true);

        }

        $branches = new BranchData();

        $branches->name = $this->post('name');

        $branches->address = $this->post('address');

        $branches->phoneNumber = $this->post('phoneNumber');

        $branches->created_at = $this->post('created_at');

        if ($id == null) {

            $response = $this->BranchModel->store($branches);

        } else {

            $response = $this->BranchModel->update($branches, $id);

        }

        return $this->returnData($response['msg'], $response['error']);

    }



    public function index_delete($id = null)

    {

        if ($id == null) {

            return $this->returnData('Parameter Id Tidak Ditemukan', true);

        }

        $response = $this->BranchModel->destroy($id);

        return $this->returnData($response['msg'], $response['error']);

    }



    public function returnData($msg, $error)

    {

        $response['error'] = $error;

        $response['message'] = $msg;

        return $this->response($response);

    }

}

class BranchData

{

    public $name;

    public $address;

    public $phoneNumber;

    public $createdAt;

}