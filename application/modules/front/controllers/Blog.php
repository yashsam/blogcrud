<?php
defined('BASEPATH') OR exit('No direct script access allowed ');
class Blog extends CI_Controller {

    function __construct() {
        parent::__construct(); 
		$this->load->model('Blog_model');
		$this->user_id = isset($this->session->get_userdata()['user_details'][0]->id)?$this->session->get_userdata()['user_details'][0]->users_id:'1';
    }

    /**
      * This function is redirect to users profile page
      * @return Void
      */
    public function index() {
    	
			
    		redirect( base_url().'front/blog/blogTable', 'refresh');
    	 
    }
	
    /**
     * This function is used for show users list
     * @return Void
     */
    public function blogTable(){
      
        //if(CheckPermission("blogs", "own_read")){
            $this->load->view('include/header');
            $this->load->view('blog_table');                
            $this->load->view('include/footer');            
        //} else {
            //$this->session->set_flashdata('messagePr', 'You don\'t have permission to access.');
            //redirect( base_url().'user/profile', 'refresh');
        //}
    }

    /**
     * This function is used to create datatable in users list page
     * @return Void
     */
    public function blogdataTable (){
        //is_login();
	    $table = 'blogs';
    	$primaryKey = 'blogs_id';
    	$columns = array(
          			array( 'db' => 'title', 'dt' => 0 ),
					array( 'db' => 'description', 'dt' => 1 ),
					array( 'db' => 'status', 'dt' => 2 ),
					array( 'db' => 'blogs_id', 'dt' => 3 )
		);

        $sql_details = array(
			'user' => $this->db->username,
			'pass' => $this->db->password,
			'db'   => $this->db->database,
			'host' => $this->db->hostname
		);
		if($this->router->module == 'front'){
			$where = array("status = 1");
		}else{
			
			$where = array("user_id = ".$this->user_id);
		}
		$output_arr = SSP::complex( $_GET, $sql_details, $table, $primaryKey, $columns, $where);
		$sess_user_id = $this->session->userdata ('user_details')[0]->users_id;
		foreach ($output_arr['data'] as $key => $value) {
			$id = $output_arr['data'][$key][count($output_arr['data'][$key])  - 1];
			
			$output_arr['data'][$key][count($output_arr['data'][$key])  - 1] = '';
			
			if(isset($_SESSION['user_details'])){
				if(CheckPermission('blogs', "all_update")){
				$output_arr['data'][$key][count($output_arr['data'][$key])  - 1] .= '<a id="btnEditRow" class="modalButtonBlog mClass"  href="javascript:;" type="button" data-src="'.$id.'" title="Edit"><i class="fa fa-pencil" data-id=""></i></a>';
				}else if(CheckPermission('blogs', "own_update") && (CheckPermission('blogs', "all_update")!=true)){
				$user_id =getRowByTableColomId($table,$id,'blogs_id','user_id');
					if($user_id==$sess_user_id){
				$output_arr['data'][$key][count($output_arr['data'][$key])  - 1] .= '<a id="btnEditRow" class="modalButtonBlog mClass"  href="javascript:;" type="button" data-src="'.$id.'" title="Edit"><i class="fa fa-pencil" data-id=""></i></a>';
					}
				}
				
				if(CheckPermission('blogs', "all_delete")){
				$output_arr['data'][$key][count($output_arr['data'][$key])  - 1] .= '<a href="'.site_url("front/blog/detail?id=$id").'" style="cursor:pointer;" data-toggle="modal" class="mClass"   title="delete"><i class="fa fa-view" ></i>view</a>';}
				else if(CheckPermission('blogs', "own_delete") && (CheckPermission('blogs', "all_delete")!=true)){
					$user_id =getRowByTableColomId($table,$id,'blogs_id','user_id');
					if($user_id==$sess_user_id){
				$output_arr['data'][$key][count($output_arr['data'][$key])  - 1] .= '<a href="'.site_url("front/blog/detail?id=$id").'" style="cursor:pointer;" data-toggle="modal" class="mClass"   title="delete"><i class="fa fa-view" ></i>view</a>';
					}
				}
           
			}else{
				
				$output_arr['data'][$key][count($output_arr['data'][$key])  - 1] .= '<a href="'.site_url("front/blog/detail?id=$id").'" class="mClass"  href="javascript:;" type="button" data-src="'.$id.'" title="Edit"><i class="fa" data-id=""></i>View</a>';
			}
		}
		echo json_encode($output_arr);
    }


    /**
     * This function is used to show popup of user to add and update
     * @return Void
     */
    public function get_modal() {
        is_login();
        if($this->input->post('id')){
            $data['userData'] = getDataByid('blogs',$this->input->post('id'),'blogs_id'); 
            echo $this->load->view('add_blog', $data, true);
        } else {
            echo $this->load->view('add_blog', '', true);
        }
        exit;
    }
	/**
     * This function is used to get details
     * @return Void
     */
	public function detail(){
		$id = $this->input->get('id');
		$data['user_data'] = getDataByid('blogs',$id,'blogs_id'); 
		
        $this->load->view('include/header');
	    $this->load->view('view_blog', $data);
	}
    /**
     * This function is used to upload file
     * @return Void
     */
    function upload() {
        foreach($_FILES as $name => $fileInfo)
        {
            $filename=$_FILES[$name]['name'];
            $tmpname=$_FILES[$name]['tmp_name'];
            $exp=explode('.', $filename);
            $ext=end($exp);
            $newname=  $exp[0].'_'.time().".".$ext; 
            $config['upload_path'] = 'assets/images/';
            $config['upload_url'] =  base_url().'assets/images/';
            $config['allowed_types'] = "gif|jpg|jpeg|png|iso|dmg|zip|rar|doc|docx|xls|xlsx|ppt|pptx|csv|ods|odt|odp|pdf|rtf|sxc|sxi|txt|exe|avi|mpeg|mp3|mp4|3gp";
            $config['max_size'] = '2000000'; 
            $config['file_name'] = $newname;
            $this->load->library('upload', $config);
            move_uploaded_file($tmpname,"assets/images/".$newname);
            return $newname;
        }
    }
  
    /**
     * This function is used to add and update users
     * @return Void
     */
    public function add_edit($id='') {   
        $data = $this->input->post();
        
        if($this->input->post('blogs_id')) {
            $id = $this->input->post('blogs_id');
        }
        if(isset($this->session->userdata ('user_details')[0]->users_id)) {
            if($this->input->post('users_id') == $this->session->userdata ('user_details')[0]->users_id){
                $redirect = 'profile';
            } else {
                $redirect = 'blogTable';
            }
        } else {
            $redirect = 'login';
        }
        
        $user_id = $this->session->userdata ('user_details')[0]->users_id;
        if($id != '') {
            $data = $this->input->post();
            if($this->input->post('status') != '') {
                $data['status'] = $this->input->post('status');
            }
            
            
            $id = $this->input->post('blogs_id');
            unset($data['fileOld']);
           
            if(isset($data['edit'])){
                unset($data['edit']);
            }
            if($data['password'] == ''){
                unset($data['password']);
            }
			$data['updated_date'] = date('Y-m-d h:i:s');
			$data['user_id'] = $user_id;
            $this->Blog_model->updateRow('blogs', 'blogs_id', $id, $data);
            $this->session->set_flashdata('messagePr', 'Your data updated Successfully..');
            redirect( base_url().'blog/'.$redirect, 'refresh');
        } else { 
            if($this->input->post('user_type') != 'admin') {
                $data = $this->input->post();
                
                $data['status'] = 1;
                if(setting_all('admin_approval') == 1) {
                    $data['status'] = 'deleted';
                }
                
                if($this->input->post('status') != '') {
                    $data['status'] = $this->input->post('status');
                }
                //$data['token'] = $this->generate_token();
				$user_id = $this->session->userdata ('user_details')[0]->users_id;
                $data['user_id'] = $user_id;
                
                unset($data['submit']);
                $this->Blog_model->insertRow('blogs', $data);
                redirect( base_url().'blog/'.$redirect, 'refresh');
            } else {
                $this->session->set_flashdata('messagePr', 'You Don\'t have this autherity ' );
                redirect( base_url().'user/registration', 'refresh');
            }
        }
    
    }


    /**
     * This function is used to delete users
     * @return Void
     */
    public function delete($id){
        is_login(); 
        $ids = explode('-', $id);
        foreach ($ids as $id) {
            $this->User_model->delete($id); 
        }
       redirect(base_url().'user/userTable', 'refresh');
    }

}