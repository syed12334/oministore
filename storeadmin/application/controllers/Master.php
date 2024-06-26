<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends CI_Controller {
    protected $data;
    public function __construct() {
        date_default_timezone_set("Asia/Kolkata");
        parent::__construct();
        $this->load->helper('utility_helper');
        $this->load->model('master_db');
        $this->load->model('home_db');
        $this->data['detail'] = "";
        $this->load->library('encryption');
        $this->load->library('form_validation');
        $this->data['session'] = ADMIN_SESSION;
         $detail = $this->session->userdata($this->data['session']);
        $this->data['id'] = $detail['id'];
        if (!$this->session->userdata($this->data['session'])) {
            redirect('Login', 'refresh');
        } else {
            $sessionval = $this->session->userdata($this->data['session']);
            $details = $this->home_db->getlogin($sessionval['name']);
            if (count($details)) {
                $this->data['detail'] = $details;
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissable"><button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>Invalid Credentials.</div>');
                redirect(base_url() . "login/logout");
            }
        }
        $this->data['header'] = $this->load->view('includes/header', $this->data, TRUE);
        $this->data['footer'] = $this->load->view('includes/footer', $this->data, TRUE);
    }
    public function index() {
        $this->load->view('index', $this->data);
    }
    /* Category  */
    public function category() {
        $this->load->view('masters/category/category', $this->data);
    }
    public function getCategorylist() {
        $where = "where status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (cname like '%$val%') ";
            $where.= " or (image like '%$val%') ";
        }
        $order_by_arr[] = "cname";
        $order_by_arr[] = "";
        $order_by_arr[] = "id";
        $order_by_def = " order by id desc";
        $query = "select * from category " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $image = "";
            if (!empty($r->image)) {
                $image.= "<img src='" . app_url() . $r->image . "'  style='width:80px'/>";
            }
            $action = '<a href=' . base_url() . "master/editcategory/" . icchaEncrypt(($r->id)) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            if ($r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", 1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Active' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            if($r->showcategory ==1) {
                $action.= "<button title='Show in homepage categories' class='btn btn-warning'  onclick='updateStatus(" . $r->id . ", 3)' >H</button>&nbsp;";
            }else {
                $action.= "<button title='Dont show in homepage categories' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 4)' style='background:#e08e0b!important;color:#fff!important;border-color:#e08e0b!important'>H</button>&nbsp;";
            }
            
            $sub_array[] = $i++;
            $sub_array[] = $action;
            // $sub_array[] = $image;
            $sub_array[] = ucfirst($r->cname);
            $sub_array[] =$r->id;
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function categoryadd() {
        $this->load->view('masters/category/categoryadd', $this->data);
    }
    public function editcategory() {
        $id = icchaDcrypt($this->uri->segment(3));
        //echo $id;exit;
        $this->data['category'] = $this->master_db->getRecords('category', ['id' => $id], '*');
        // echo $this->db->last_query();exit;
        $this->load->view('masters/category/categoryedit', $this->data);
    }
    public function categorysave() {
        // echo "<pre>";print_r($_POST);print_r($_FILES);exit;
        $id = $this->input->post('cid');
        $cname = trim(html_escape($this->input->post('cname', true)));
        $orderno = trim(html_escape($this->input->post('orderno', true)));
        if ($id == "") {
            $getCategory = $this->master_db->getRecords('category',['cname'=>$cname],'*');
            if(count($getCategory) >0) {
                 $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Category already exists</div>');
                 redirect(base_url() . 'master/categoryadd');
            }else {
                    $db['cname'] = $cname;
                    if (!empty($_FILES['image']['name'])) {
                        //unlink("$images");
                        $uploadPath = '../assets/category/';
                        $config['upload_path'] = $uploadPath;
                        $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                        $ext = pathinfo($_FILES["image"]['name'], PATHINFO_EXTENSION);
                        $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                        $config['file_name'] = $new_name;
                        // $config['max_width'] = 295;
                        // $config['max_height'] = 672;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('image')) {
                            $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                            redirect(base_url() . 'master/categoryadd');
                        } else {
                            $upload_data = $this->upload->data();
                            $db['image'] = 'assets/category/' . $upload_data['file_name'];
                        }
                    }
                    if (!empty($_FILES['adimage']['name'])) {
                        //unlink("$images");
                        $uploadPath = '../assets/category/';
                        $config['upload_path'] = $uploadPath;
                        $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                        $ext = pathinfo($_FILES["adimage"]['name'], PATHINFO_EXTENSION);
                        $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                        $config['file_name'] = $new_name;
                        // $config['max_width'] = 295;
                        // $config['max_height'] = 672;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('adimage')) {
                            $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                            redirect(base_url() . 'master/categoryadd');
                        } else {
                            $upload_data = $this->upload->data();
                            $db['ads_image'] = 'assets/category/' . $upload_data['file_name'];
                        }
                    }
                    $db['page_url'] = $this->master_db->create_unique_slug($cname,'category','page_url');
                    $db['orderno'] = $orderno;
                    $db['created_at'] = date('Y-m-d H:i:s');
                    $in = $this->master_db->insertRecord('category', $db);
                    if ($in > 0) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Inserted successfully</div>');
                        redirect(base_url() . 'master/category');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in inserting</div>');
                        redirect(base_url() . 'master/categoryadd');
                    }
            }
        } else {
            $ids = $this->input->post('cid');
            //echo "<pre>";print_r($_POST);print_r($_FILES);exit;
            $getCategory = $this->master_db->getRecords('category',['cname'=>$cname],'*');
            // if(count($getCategory) >0) {
            //     $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Category already exists</div>');
            //             redirect(base_url() . 'master/category');
            // }else {
                $db['cname'] = $cname;
                if (!empty($_FILES['image']['name'])) {
                    //unlink("$images");
                    $uploadPath = '../assets/category/';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                    $ext = pathinfo($_FILES["image"]['name'], PATHINFO_EXTENSION);
                    $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                    $config['file_name'] = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('image')) {
                        $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                        redirect(base_url() . 'master/editcategory');
                    } else {
                        $upload_data1 = $this->upload->data();
                        $db['image'] = 'assets/category/' . $upload_data1['file_name'];
                    }
                }
                 if (!empty($_FILES['adimage']['name'])) {
                        //unlink("$images");
                        $uploadPath = '../assets/category/';
                        $config['upload_path'] = $uploadPath;
                        $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                        $ext = pathinfo($_FILES["adimage"]['name'], PATHINFO_EXTENSION);
                        $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                        $config['file_name'] = $new_name;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('adimage')) {
                            $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                            redirect(base_url() . 'master/categoryadd');
                        } else {
                            $upload_data = $this->upload->data();
                            $db['ads_image'] = 'assets/category/' . $upload_data['file_name'];
                        }
                    }
                $db['page_url'] = $this->master_db->create_unique_slug($cname,'category','page_url');
                $db['orderno'] = $orderno;
                $db['modified_at'] = date('Y-m-d H:i:s');
                $update = $this->master_db->updateRecord('category', $db, ['id' => $id]);
                if ($update > 0) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated successfully</div>');
                    redirect(base_url() . 'master/category');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in updating</div>');
                    redirect(base_url() . 'master/category');
                }   
            //}
        }
    }
    public function setcategoryStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('category', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'));
            $this->master_db->updateRecord('category', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'));
            $this->master_db->updateRecord('category', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
        else if ($status ==3) {
            $where_data = array('showcategory' => 0);
            $this->master_db->updateRecord('category', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
        else if ($status ==4) {
            $where_data = array('showcategory' => 1);
            $this->master_db->updateRecord('category', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    /* Brand  */
    public function brand() {
        $this->load->view('masters/brand/brand', $this->data);
    }
    public function getbrandlist() {
        $where = "where status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (name like '%$val%') ";
            $where.= " or (brand_img like '%$val%') ";
        }
        $order_by_arr[] = "name";
        $order_by_arr[] = "";
        $order_by_arr[] = "id";
        $order_by_def = " order by id desc";
        $query = "select * from brand " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $image = "";
            if (!empty($r->brand_img)) {
                $image.= "<img src='" . app_url() . $r->brand_img . "'  style='width:80px'/>";
            }
            $action = '<a href=' . base_url() . "master/editbrand/" . icchaEncrypt(($r->id)) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            if ($r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", 1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Active' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = ucfirst($r->name);
            $sub_array[] = $r->id;
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function brandadd() {
        $this->load->view('masters/brand/brandadd', $this->data);
    }
    public function editbrand() {
        $id = icchaDcrypt($this->uri->segment(3));
        //echo $id;exit;
        $this->data['category'] = $this->master_db->getRecords('brand', ['id' => $id], '*');
        // echo $this->db->last_query();exit;
        $this->load->view('masters/brand/brandedit', $this->data);
    }
    public function brandsave() {
        // echo "<pre>";print_r($_POST);print_r($_FILES);exit;
        $id = $this->input->post('cid');
        $cname = trim(html_escape($this->input->post('cname', true)));
        $getBrand = $this->master_db->getRecords('brand',['name'=>$cname],'*');
        if(count($getBrand) >0) {
                 $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Brand already exists try another</div>');
                    redirect(base_url() . 'master/brand');
        }else {
                    if ($id == "") {
                $db['name'] = $cname;
                if (!empty($_FILES['image']['name'])) {
                    //unlink("$images");
                    $uploadPath = '../assets/brand/';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                    $config['max_width'] = 206;
                    $config['max_height'] = 93;
                    $ext = pathinfo($_FILES["image"]['name'], PATHINFO_EXTENSION);
                    $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                    $config['file_name'] = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('image')) {
                        $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                        redirect(base_url() . 'master/brandadd');
                    } else {
                        $upload_data = $this->upload->data();
                        $db['brand_img'] = 'assets/brand/' . $upload_data['file_name'];
                    }
                }
                $db['created_at'] = date('Y-m-d H:i:s');
                $in = $this->master_db->insertRecord('brand', $db);
                if ($in > 0) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Inserted successfully</div>');
                    redirect(base_url() . 'master/brand');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in inserting</div>');
                    redirect(base_url() . 'master/brand');
                }
            } else {
                $ids = $this->input->post('cid');
                //echo "<pre>";print_r($_POST);print_r($_FILES);exit;
                $db['name'] = $cname;
                if (!empty($_FILES['image']['name'])) {
                    //unlink("$images");
                    $uploadPath = '../assets/brand/';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                    $config['max_width'] = 206;
                    $config['max_height'] = 93;
                    $ext = pathinfo($_FILES["image"]['name'], PATHINFO_EXTENSION);
                    $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                    $config['file_name'] = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('image')) {
                        $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                        redirect(base_url() . 'master/editbrand');
                    } else {
                        $upload_data1 = $this->upload->data();
                        $db['brand_img'] = 'assets/brand/' . $upload_data1['file_name'];
                    }
                }
                $db['modified_at'] = date('Y-m-d H:i:s');
                $update = $this->master_db->updateRecord('brand', $db, ['id' => $id]);
                if ($update > 0) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated successfully</div>');
                    redirect(base_url() . 'master/brand');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in updating</div>');
                    redirect(base_url() . 'master/brand');
                }
            }
        }
        
    }
    public function setbrandStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('brand', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('brand', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('brand', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    /* Subcategory  */
    public function subcategory() {
        $this->load->view('masters/subcategory/subcategory', $this->data);
    }
    public function getsubcategorylist() {
        $where = "where s.status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (s.sname like '%$val%') ";
            $where.= " or (s.sub_img like '%$val%') ";
            $where.= " or (c.cname like '%$val%') ";
        }
        $order_by_arr[] = "s.sname";
        $order_by_arr[] = "";
        $order_by_arr[] = "s.id";
        $order_by_def = " order by s.id desc";
        $query = "select s.id,s.cat_id,s.sname,s.sub_img,s.created_at,s.status,c.cname from subcategory s left join category c on c.id = s.cat_id " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $image = "";
            if (!empty($r->sub_img)) {
                $image.= "<img src='" . app_url() . $r->sub_img . "'  style='width:80px'/>";
            }
            $action = '<a href=' . base_url() . "master/editsubcategory/" . icchaEncrypt(($r->id)) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            if ($r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", 1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Active' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            // $sub_array[] = $image;
            $sub_array[] = $r->cname;
            $sub_array[] = ucfirst($r->sname);
            $sub_array[] = $r->id;
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function subcategoryadd() {
        $this->data['category'] = $this->master_db->getRecords('category', ['status' => 0], 'id,cname');
        $this->load->view('masters/subcategory/subcategoryadd', $this->data);
    }
    public function editsubcategory() {
        $this->data['category'] = $this->master_db->getRecords('category', ['status' => 0], 'id,cname');
        $id = icchaDcrypt($this->uri->segment(3));
        //echo $id;exit;
        $this->data['subcategory'] = $this->master_db->getRecords('subcategory', ['id' => $id], '*');
        // echo $this->db->last_query();exit;
        $this->load->view('masters/subcategory/subcategoryedit', $this->data);
    }
    public function subcategorysave() {
        //echo "<pre>";print_r($_POST);print_r($_FILES);exit;
        $id = $this->input->post('cid');
        $cname = trim(html_escape($this->input->post('cname', true)));
        $cat_id = trim(html_escape($this->input->post('sid', true)));
        $getSubcategory = $this->master_db->getRecords('subcategory',['sname'=>$cname],'*');
        $getCategory = $this->master_db->getRecords('category',['id'=>$cat_id],'page_url');
        if(count($getSubcategory) >0) {
             $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Subcategory already exists try another</div>');
                redirect(base_url() . 'subcategory');
        }else {
              if (empty($id) && $id == "") {
                        $db['cat_id'] = $cat_id;
                        $db['sname'] = $cname;
                        if (!empty($_FILES['image']['name'])) {
                            //unlink("$images");
                            $uploadPath = '../assets/subcategory/';
                            $config['upload_path'] = $uploadPath;
                            $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                            $ext = pathinfo($_FILES["image"]['name'], PATHINFO_EXTENSION);
                            $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                            $config['file_name'] = $new_name;
                            $config['max_width'] = 300;
                            $config['max_height'] = 300;
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            if (!$this->upload->do_upload('image')) {
                                $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                                redirect(base_url() . 'subcategoryadd');
                            } else {
                                $upload_data = $this->upload->data();
                                $db['sub_img'] = 'assets/subcategory/' . $upload_data['file_name'];
                            }
                        }
                        $db['page_url'] = $getCategory[0]->page_url."-".$this->master_db->create_unique_slug($cname,'subcategory','page_url');
                        $db['created_at'] = date('Y-m-d H:i:s');
                        $in = $this->master_db->insertRecord('subcategory', $db);
                        if ($in > 0) {
                            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Inserted successfully</div>');
                            redirect(base_url() . 'subcategory');
                        } else {
                            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in inserting</div>');
                            redirect(base_url() . 'subcategoryadd');
                        }
                    } else if (!empty($id) && $id != "") {
                        //echo "updated";exit;
                        $ids = $this->input->post('cid');
                        //echo "<pre>";print_r($_POST);print_r($_FILES);exit;
                        $db['cat_id'] = $cat_id;
                        $db['sname'] = $cname;
                        if (!empty($_FILES['image']['name'])) {
                            //unlink("$images");
                            $uploadPath = '../assets/subcategory/';
                            $config['upload_path'] = $uploadPath;
                            $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                            $ext = pathinfo($_FILES["image"]['name'], PATHINFO_EXTENSION);
                            $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                            $config['file_name'] = $new_name;
                            $config['max_width'] = 300;
                            $config['max_height'] = 300;
                            $this->load->library('upload', $config);
                            $this->upload->initialize($config);
                            if (!$this->upload->do_upload('image')) {
                                $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                                redirect(base_url() . 'master/editsubcategory');
                            } else {
                                $upload_data1 = $this->upload->data();
                                $db['sub_img'] = 'assets/subcategory/' . $upload_data1['file_name'];
                            }
                        }
                        $db['page_url'] = $getCategory[0]->page_url."-".$this->master_db->create_unique_slug($cname,'subcategory','page_url');
                        $db['modified_at'] = date('Y-m-d H:i:s');
                        $update = $this->master_db->updateRecord('subcategory', $db, ['id' => $id]);
                        if ($update > 0) {
                            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated successfully</div>');
                            redirect(base_url() . 'subcategory');
                        } else {
                            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in updating</div>');
                            redirect(base_url() . 'master/editsubcategory');
                        }
                    }
        }
    
    }
    public function setsubcategoryStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('subcategory', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('subcategory', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('subcategory', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    /* Sub Subcategory  */
    public function subsubcategory() {
        $this->load->view('masters/subsubcategory/subsubcategory', $this->data);
    }
    public function getsubsubcategorylist() {
        $where = "where ss.status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (ss.ssname like '%$val%') ";
            $where.= " or (ss.subsub_img like '%$val%') ";
            $where.= " or (s.sname like '%$val%') ";
        }
        $order_by_arr[] = "ss.sname";
        $order_by_arr[] = "";
        $order_by_arr[] = "ss.id";
        $order_by_def = " order by ss.id desc";
        $query = "select ss.id,ss.sub_id,ss.ssname,ss.subsub_img,ss.created_at,ss.status,s.sname from subsubcategory ss  left join subcategory s on s.id = ss.sub_id " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $image = "";
            if (!empty($r->subsub_img)) {
                $image.= "<img src='" . app_url() . $r->subsub_img . "'  style='width:80px'/>";
            }
            $action = '<a href=' . base_url() . "master/editsubsubcategory/" . icchaEncrypt(($r->id)) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            if ($r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", 1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Active' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            // $sub_array[] = $image;
            $sub_array[] = $r->sname;
            $sub_array[] = ucfirst($r->ssname);
            $sub_array[] = $r->id;
            // $sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> ".date('d M y',strtotime($r->created_at))."<br/>"."<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> ".date('h:i:s A',strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function subsubcategoryadd() {
        $this->data['category'] = $this->master_db->getRecords('subcategory', ['status' => 0], 'id,sname as cname');
        $this->load->view('masters/subsubcategory/subsubcategoryadd', $this->data);
    }
    public function editsubsubcategory() {
        $this->data['category'] = $this->master_db->getRecords('subcategory', ['status' => 0], 'id,sname as cname');
        $id = icchaDcrypt($this->uri->segment(3));
        //echo $id;exit;
        $this->data['subcategory'] = $this->master_db->getRecords('subsubcategory', ['id' => $id], '*');
        // echo $this->db->last_query();exit;
        $this->load->view('masters/subsubcategory/subsubcategoryedit', $this->data);
    }
    public function subsubcategorysave() {
        //echo "<pre>";print_r($_POST);print_r($_FILES);exit;
        $id = $this->input->post('cid');
        $cname = trim(html_escape($this->input->post('cname', true)));
        $cat_id = trim(html_escape($this->input->post('sid', true)));
        $getSubcategory = $this->master_db->getRecords('subcategory',['id'=>$cat_id],'page_url');
        $getsubSubcategory = $this->master_db->getRecords('subsubcategory',['ssname'=>$cname],'*');
        if(count($getsubSubcategory) >0) {
                 $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Subsubcategory already exists try another</div>');
                        redirect(base_url() . 'subsubcategory');
        }else {
                    if (empty($id) && $id == "") {
                    $db['sub_id'] = $cat_id;
                    $db['ssname'] = $cname;
                    if (!empty($_FILES['image']['name'])) {
                        //unlink("$images");
                        $uploadPath = '../assets/subsubcategory/';
                        $config['upload_path'] = $uploadPath;
                        $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                        $config['max_width'] = 300;
                        $config['max_height'] = 300;
                        $ext = pathinfo($_FILES["image"]['name'], PATHINFO_EXTENSION);
                        $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                        $config['file_name'] = $new_name;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('image')) {
                            $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                            redirect(base_url() . 'subcategoryadd');
                        } else {
                            $upload_data = $this->upload->data();
                            $db['subsub_img'] = 'assets/subsubcategory/' . $upload_data['file_name'];
                        }
                    }
                    $db['page_url'] = $getSubcategory[0]->page_url."-".$this->master_db->create_unique_slug($cname,'subsubcategory','page_url');
                    $db['created_at'] = date('Y-m-d H:i:s');
                    $in = $this->master_db->insertRecord('subsubcategory', $db);
                    if ($in > 0) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Inserted successfully</div>');
                        redirect(base_url() . 'subsubcategory');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in inserting</div>');
                        redirect(base_url() . 'subsubcategoryadd');
                    }
                } else if (!empty($id) && $id != "") {
                    //echo "updated";exit;
                    $ids = $this->input->post('cid');
                    //echo "<pre>";print_r($_POST);print_r($_FILES);exit;
                    $db['sub_id'] = $cat_id;
                    $db['ssname'] = $cname;
                    if (!empty($_FILES['image']['name'])) {
                        //unlink("$images");
                        $uploadPath = '../assets/subsubcategory/';
                        $config['upload_path'] = $uploadPath;
                        $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                        $config['max_width'] = 300;
                        $config['max_height'] = 300;
                        $ext = pathinfo($_FILES["image"]['name'], PATHINFO_EXTENSION);
                        $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                        $config['file_name'] = $new_name;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('image')) {
                            $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                            redirect(base_url() . 'master/editsubsubcategory');
                        } else {
                            $upload_data1 = $this->upload->data();
                            $db['subsub_img'] = 'assets/subsubcategory/' . $upload_data1['file_name'];
                        }
                    }
                    $db['page_url'] = $getSubcategory[0]->page_url."-".$this->master_db->create_unique_slug($cname,'subsubcategory','page_url');
                    $db['modified_at'] = date('Y-m-d H:i:s');
                    $update = $this->master_db->updateRecord('subsubcategory', $db, ['id' => $id]);
                    if ($update > 0) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated successfully</div>');
                        redirect(base_url() . 'subsubcategory');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in updating</div>');
                        redirect(base_url() . 'master/editsubsubcategory');
                    }
                }
        }
    }
    public function setsubsubcategoryStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('subsubcategory', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('subsubcategory', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('subsubcategory', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    /****** Register **********/
    public function register() {
        //echo "<pre>";print_r($_POST);exit;
        $type = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('type', true))));
        $title = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('title', true))));
        $price = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('price', true))));
        $vmonths = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('vmonths', true))));
        $noftype = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('noftype', true))));
        $noofpics = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('noofpics', true))));
        $id = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('id', true))));
        if (!empty($id)) {
            $db['type'] = $type;
            $db['title'] = $title;
            $db['pprice'] = $price;
            $db['nmonths'] = $vmonths;
            $db['nproperties'] = $noftype;
            $db['npictures'] = $noofpics;
            $db['modified_date'] = date('Y-m-d H:i:s');
            $update = $this->master_db->updateRecord('packages', $db, ['id' => $id]);
            if ($update) {
                $results = ['status' => true, 'msg' => 'Updated successfully'];
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated successfully</div>');
                echo json_encode($results);
            } else {
                $results = ['status' => false, 'msg' => 'Error in updating '];
                echo json_encode($results);
            }
        } else {
            if (!empty($type) && !empty($title) && !empty($price) && !empty($vmonths) && !empty($noftype)) {
                $db['type'] = $type;
                $db['title'] = $title;
                $db['pprice'] = $price;
                $db['nmonths'] = $vmonths;
                $db['nproperties'] = $noftype;
                $db['npictures'] = $noofpics;
                $db['status'] = 0;
                $db['created_date'] = date('Y-m-d H:i:s');
                $in = $this->master_db->insertRecord('packages', $db);
                if ($in) {
                    $result = ['status' => true, 'msg' => 'Inserted successfully'];
                    echo json_encode($result);
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Inserted successfully</div>');
                } else {
                    $result = ['status' => false, 'msg' => 'Error in inserting '];
                }
            } else {
                $result = ['status' => false, 'msg' => 'Required fields are missing'];
                echo json_encode($result);
            }
        }
    }
    /* Ads  */
    public function ads() {
        $this->load->view('masters/ads/ads', $this->data);
    }
    public function getAds() {
        $where = "where status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (ad_name like '%$val%') ";
            $where.= " or (ad_link like '%$val%') ";
        }
        $order_by_arr[] = "ad_name";
        $order_by_arr[] = "";
        $order_by_arr[] = "id";
        $order_by_def = " order by id desc";
        $query = "select * from ads " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $image = "";
            if (!empty($r->ad_banner)) {
                $image.= "<img src='" . app_url() . $r->ad_banner . "'  style='width:80px'/>";
            }
            $action = '<a href=' . base_url() . "master/editads/" . icchaEncrypt(($r->id)) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            if ($r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", 1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Active' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $image;
            $sub_array[] = ucfirst($r->ad_name);
            $sub_array[] = $r->ad_link;
            // $sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> ".date('d M y',strtotime($r->created_at))."<br/>"."<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> ".date('h:i:s A',strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function adsadd() {
        $this->load->view('masters/ads/adsadd', $this->data);
    }
    public function editads() {
        $id = icchaDcrypt($this->uri->segment(3));
        //echo $id;exit;
        $this->data['ads'] = $this->master_db->getRecords('ads', ['id' => $id], '*');
        // echo $this->db->last_query();exit;
        $this->load->view('masters/ads/adsedit', $this->data);
    }
    public function adssave() {
        // echo "<pre>";print_r($_POST);print_r($_FILES);exit;
        $id = $this->input->post('cid');
        $cname = trim(html_escape($this->input->post('cname', true)));
        $adlink = trim(html_escape($this->input->post('adlink', true)));
        $this->form_validation->set_rules('cname','Ads Name','regex_match[/^([A-Za-z0-9% ])+$/i]|max_length[50]',['regex_match'=>'Only characters, numbers and percentage are allowed','max_length'=>'Maximum 50 characters are allowed']);
        
        if($id =="") {
            if(empty($_FILES['image']['name'][0])) {
                $this->form_validation->set_rules('image','Ads Image ','required',['required'=>'Please upload ads image']);       
            }
            $this->form_validation->set_rules('adlink','Ad Link','valid_url',['valid_url'=>'Enter valid url']);
        }
        if($this->form_validation->run() ==TRUE) {
                 if ($id == "") {
                    if (!empty($_FILES['image']['name'])) {
                        //unlink("$images");
                        $uploadPath = '../assets/ads/';
                        $config['upload_path'] = $uploadPath;
                        $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                        $config['max_width'] = 610;
                        $config['max_height'] = 200;
                        $ext = pathinfo($_FILES["image"]['name'], PATHINFO_EXTENSION);
                        $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                        $config['file_name'] = $new_name;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('image')) {
                            
                             $resul = array(
                             'status'   => false,
                            'msg' =>"<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>",
                            'csrf_token'=> $this->security->get_csrf_hash()
                            );
                        } else {
                            $upload_data = $this->upload->data();
                            $db['ad_banner'] = 'assets/ads/' . $upload_data['file_name'];
                        }
                    }
                    $db['ad_name'] = $cname;
                    $db['ad_link'] = $adlink;
                    $db['created_at'] = date('Y-m-d H:i:s');
                    $in = $this->master_db->insertRecord('ads', $db);
                    if ($in > 0) {
                       
                        $resul = array(
                             'status'   => true,
                            'msg' =>"Inserted successfully",
                            'csrf_token'=> $this->security->get_csrf_hash()
                            );
                    } else {
                        $resul = array(
                             'status'   => false,
                            'msg' =>"Error in inserting",
                            'csrf_token'=> $this->security->get_csrf_hash()
                            );
                    }
                } else {
                    $ids = $this->input->post('cid');
                    //echo "<pre>";print_r($_POST);print_r($_FILES);exit;
                    if (!empty($_FILES['image']['name'])) {
                        //unlink("$images");
                        $uploadPath = '../assets/ads/';
                        $config['upload_path'] = $uploadPath;
                        $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                        $config['max_width'] = 610;
                        $config['max_height'] = 200;
                        $ext = pathinfo($_FILES["image"]['name'], PATHINFO_EXTENSION);
                        $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                        $config['file_name'] = $new_name;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('image')) {
                          $resul = array(
                             'status'   => false,
                            'msg' =>"<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>",
                            'csrf_token'=> $this->security->get_csrf_hash()
                            );
                        } else {
                            $upload_data1 = $this->upload->data();
                            $db['ad_banner'] = 'assets/ads/' . $upload_data1['file_name'];
                        }
                    }
                    $db['ad_name'] = $cname;
                    $db['ad_link'] = $adlink;
                    $db['modified_at'] = date('Y-m-d H:i:s');
                    $update = $this->master_db->updateRecord('ads', $db, ['id' => $id]);
                    if ($update > 0) {
                         $resul = array(
                         'status'   => true,
                        'msg' =>"Updated successfully",
                        'csrf_token'=> $this->security->get_csrf_hash()
                        );
                    } else {
                        $resul = array(
                         'status'   => false,
                        'msg' =>"Error in updating",
                        'csrf_token'=> $this->security->get_csrf_hash()
                        );
                    }
                }
        }else {
                 $resul = array(
                     'formerror'   => false,
                    'ads_error' => form_error('cname'),
                    'link_error' => form_error('adlink'),
                    'image_error' => form_error('image'),
                    'csrf_token'=> $this->security->get_csrf_hash()
                );
        }
        echo json_encode($resul);
       
    }
    public function setadsStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('ads', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('ads', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('ads', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    public function users() {
        $this->load->view('users', $this->data);
    }
     public function getuserlist() {
        $where = "where status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (name like '%$val%') ";
            $where.= " or (email like '%$val%') ";
            $where.= " or (phone like '%$val%') ";
        }
        $order_by_arr[] = "name";
        $order_by_arr[] = "";
        $order_by_arr[] = "u_id";
        $order_by_def = " order by u_id desc";
        $query = "select * from users " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            
            $action = "";
            $sub_array = array();
            if ((int)$r->status == 0) {
                $action.= "<button title='Deactive' class='btns' onclick='updateStatus(" . $r->u_id . ", -1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Activate' class='btns' onclick='updateStatus(" . $r->u_id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
           
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $r->name;
            $sub_array[] = $r->email;
            $sub_array[] = $r->phone;
            $sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> " . date('d M y', strtotime($r->created_at)) . "<br/>" . "<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> " . date('h:i:s A', strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function setusersStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        $where_data = array('status' => $status, 'modified_date' => date('Y-m-d H:i:s'));
        $this->master_db->updateRecord('users', $where_data, array('id' => $id));
        echo json_encode(['status' => 1, "csrf_token" => $this->security->get_csrf_hash() ]);
    }
    public function getuserreviews() {
        $where = "where status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (name like '%$val%') ";
            $where.= " or (email like '%$val%') ";
            $where.= " or (phone like '%$val%') ";
            $where.= " or (address like '%$val%') ";
        }
        $order_by_arr[] = "b=name";
        $order_by_arr[] = "";
        $order_by_arr[] = "id";
        $order_by_def = " order by id desc";
        $query = "select * from users " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            if ((int)$r->status == 0) {
                $action.= "<button title='Deactive' class='btns' onclick='updateStatus(" . $r->id . ", -1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Activate' class='btns' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $r->name;
            $sub_array[] = $r->email;
            $sub_array[] = $r->phone;
            $sub_array[] = $r->address;
            $sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> " . date('d M y', strtotime($r->created_at)) . "<br/>" . "<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> " . date('h:i:s A', strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    /****** States ******/
    public function states() {
        $this->load->view('masters/states/states', $this->data);
    }
    public function getStates() {
        $where = "where status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (name like '%$val%') ";
        }
        $order_by_arr[] = "name";
        $order_by_arr[] = "";
        $order_by_arr[] = "id";
        $order_by_def = " order by id desc";
        $query = "select * from states " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $action.= '<a href=' . base_url() . "master/editstates?id=" . icchaEncrypt($r->id) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            $status = '';
            $type = "";
            if ((int)$r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", -1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Activate' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            // $action .= "<button title='View Detail' class='btns' onClick='popUp()'><i class='fas fa-eye' ></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = ucfirst($r->name);
            $sub_array[] = $r->id;
            // $sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> ".date('d M y',strtotime($r->created_at))."<br/>"."<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> ".date('h:i:s A',strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function stateadd() {
        $this->load->view('masters/states/statesadd', $this->data);
    }
    public function savestates() {
        //echo "<pre>";print_r($_POST);exit;
        $title = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('title', true))));
        $id = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('id', true))));
        $getState = $this->master_db->getRecords('states',['name'=>$title],'*');
        if(count($getState) >0) {
                  $results = ['status' => false, 'msg' => 'State already exists try another ', 'csrf_token' => $this->security->get_csrf_hash() ];
                    echo json_encode($results);
        }else {
                if (!empty($id)) {
                $db['name'] = $title;
                $db['modified_date'] = date('Y-m-d H:i:s');
                $update = $this->master_db->updateRecord('states', $db, ['id' => $id]);
                if ($update) {
                    $results = ['status' => true, 'msg' => 'Updated successfully', 'csrf_token' => $this->security->get_csrf_hash() ];
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated successfully</div>');
                    echo json_encode($results);
                } else {
                    $results = ['status' => false, 'msg' => 'Error in updating ', 'csrf_token' => $this->security->get_csrf_hash() ];
                    echo json_encode($results);
                }
            } else {
                if (!empty($title)) {
                    $db['name'] = $title;
                    $db['status'] = 0;
                    $db['created_at'] = date('Y-m-d H:i:s');
                    $in = $this->master_db->insertRecord('states', $db);
                    if ($in) {
                        $result = ['status' => true, 'msg' => 'Inserted successfully', 'csrf_token' => $this->security->get_csrf_hash() ];
                        echo json_encode($result);
                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Inserted successfully</div>');
                    } else {
                        $result = ['status' => false, 'msg' => 'Error in inserting ', 'csrf_token' => $this->security->get_csrf_hash() ];
                    }
                } else {
                    $result = ['status' => false, 'msg' => 'Required fields are missing', 'csrf_token' => $this->security->get_csrf_hash() ];
                    echo json_encode($result);
                }
            }  
        }
    }
    public function setstatesStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('states', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == - 1) {
            $where_data = array('status' => $status, 'modified_date' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('states', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_date' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('states', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    public function editstates() {
        $this->load->library('encrypt');
        $id = icchaDcrypt($_GET['id']);
        //echo $id;exit;
        $getStates = $this->master_db->getRecords('states', ['id' => $id], '*');
        //echo $this->db->last_query();exit;
        $this->data['property'] = $getStates;
        $this->load->view('masters/states/statesadd', $this->data);
    }
    /****** City ******/
    public function city() {
        $this->load->view('masters/city/cities', $this->data);
    }
    public function getcity() {
        $where = "where c.status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (c.cname like '%$val%') ";
            $where.= " or (s.name like '%$val%') ";
            $where.= " or (c.created_at like '%$val%') ";
        }
        $order_by_arr[] = "c.name";
        $order_by_arr[] = "";
        $order_by_arr[] = "c.id";
        $order_by_def = " order by c.id desc";
        $query = "select c.id,c.cname,s.name as sname,c.created_at,c.status,c.noofdays from cities c left join states s on s.id = c.sid  " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $action.= '<a href=' . base_url() . "master/editcity?id=" . icchaEncrypt($r->id) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            $status = '';
            $type = "";
            if ((int)$r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", -1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Activate' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            // $action .= "<button title='View Detail' class='btns' onClick='popUp()'><i class='fas fa-eye' ></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $r->cname;
            $sub_array[] = $r->noofdays;
            $sub_array[] = $r->sname;
            $sub_array[] = $r->id;
            // $sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> ".date('d M y',strtotime($r->created_at))."<br/>"."<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> ".date('h:i:s A',strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function cityadd() {
        $getStates = $this->master_db->getRecords('states', ['status' => 0], 'id,name', 'name asc');
        $this->data['states'] = $getStates;
        $this->load->view('masters/city/cityadd', $this->data);
    }
    public function setcityStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('cities', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == - 1) {
            $where_data = array('status' => $status, 'modified_date' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('cities', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_date' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('cities', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    public function savecity() {
        //echo "<pre>";print_r($_POST);exit;
        $state = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('state', true))));
        $city = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('city', true))));
        $id = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('id', true))));
        $noofdays = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('noofdays', true))));
        $getCity = $this->master_db->getRecords('cities',['cname'=>$city],'*');
        if(count($getCity) >0) {
            $results = ['status' => false, 'msg' => 'City already exists try another', 'csrf_token' => $this->security->get_csrf_hash() ];
                    echo json_encode($results);
        }else {
                    if (!empty($id)) {
                $db['sid'] = $state;
                $db['cname'] = $city;
                $db['noofdays'] = $noofdays;
                $db['modified_date'] = date('Y-m-d H:i:s');
                $update = $this->master_db->updateRecord('cities', $db, ['id' => $id]);
                if ($update) {
                    $results = ['status' => true, 'msg' => 'Updated successfully', 'csrf_token' => $this->security->get_csrf_hash() ];
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated successfully</div>');
                    echo json_encode($results);
                } else {
                    $results = ['status' => false, 'msg' => 'Error in updating ', 'csrf_token' => $this->security->get_csrf_hash() ];
                    echo json_encode($results);
                }
            } else {
                if (!empty($city)) {
                    $db['sid'] = $state;
                    $db['cname'] = $city;
                    $db['noofdays'] = $noofdays;
                    $db['status'] = 0;
                    $db['created_at'] = date('Y-m-d H:i:s');
                    $in = $this->master_db->insertRecord('cities', $db);
                    if ($in) {
                        $result = ['status' => true, 'msg' => 'Inserted successfully', 'csrf_token' => $this->security->get_csrf_hash() ];
                        echo json_encode($result);
                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Inserted successfully</div>');
                    } else {
                        $result = ['status' => false, 'msg' => 'Error in inserting ', 'csrf_token' => $this->security->get_csrf_hash() ];
                    }
                } else {
                    $result = ['status' => false, 'msg' => 'Required fields are missing', 'csrf_token' => $this->security->get_csrf_hash() ];
                    echo json_encode($result);
                }
            }
        }
        
    }
    public function editcity() {
        $id = icchaDcrypt($_GET['id']);
        $getStates = $this->master_db->getRecords('states', ['status' => 0], 'id,name', 'name asc');
        $this->data['states'] = $getStates;
        //echo $id;exit;
        $cities = $this->master_db->getRecords('cities', ['id' => $id], '*');
        //echo $this->db->last_query();exit;
        $this->data['city'] = $cities;
        $this->load->view('masters/city/cityadd', $this->data);
    }
    /****** Sliders ******/
    public function sliders() {
        $this->load->view('masters/sliders/slider', $this->data);
    }
    public function getslider() {
        $where = "where status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (image like '%$val%') ";
            $where.= " or (created_at like '%$val%') ";
        }
        $order_by_arr[] = "image";
        $order_by_arr[] = "";
        $order_by_arr[] = "id";
        $order_by_def = " order by id desc";
        $query = "select id,image, created_at,status,type from slider_img   " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $action.= '<a href=' . base_url() . "master/editslider?id=" . icchaEncrypt($r->id) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            $status = '';
            $type = "";
            if ((int)$r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", -1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Activate' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            // $action .= "<button title='View Detail' class='btns' onClick='popUp()'><i class='fas fa-eye' ></i></button>&nbsp;";
            $image = "";$types = "";
            if (!empty($r->image)) {
                $image.= "<img src='" . app_url() . $r->image . "' style='width:100px'/>";
            }
            if($r->type ==1) {
                $types .="Swarnagowri";
            }else if($r->type ==2) {
                $types .="Specialoffers";
            }
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $image;
            $sub_array[] = $types;
            // $sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> ".date('d M y',strtotime($r->created_at))."<br/>"."<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> ".date('h:i:s A',strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function slideradd() {
        $getStates = $this->master_db->getRecords('states', ['status' => 0], 'id,name', 'name asc');
        $this->data['site_type'] = $this->master_db->getRecords('site_type',['id !='=>-1],'*');
        $this->data['states'] = $getStates;
        $this->load->view('masters/sliders/slideradd', $this->data);
    }
    public function setsliderStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('slider_img', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == - 1) {
            $where_data = array('status' => $status, 'modified_date' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('slider_img', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_date' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('slider_img', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    public function saveslider() {
        // echo "<pre>";print_r($_FILES);print_r($_POST);exit;
        $id = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('id', true))));
        $stitle = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('stitle', true))));
        $stagline = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('stagline', true))));
        $link = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('link', true))));
        $type = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('type', true))));
        if (!empty($id)) {
            if (!empty($_FILES['sfile']['name'])) {
                $uploadPath = '../assets/sliders/';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'jpg|png|PNG|JPEG';
                $config['max_width'] = 1903;
                $config['max_height'] = 520;
                $ext = pathinfo($_FILES["sfile"]['name'], PATHINFO_EXTENSION);
                $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                $config['file_name'] = $new_name;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('sfile')) {
                    $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                    redirect('slideradd');
                } else {
                    $upload_data = $this->upload->data();
                    $db['image'] = 'assets/sliders/' . $upload_data['file_name'];
                }
            }
            $db['stitle'] = $stitle;
            $db['stagline'] = $stagline;
            $db['sliderlink'] = $link;
            $db['type'] = $type;
            $db['modified_date'] = date('Y-m-d H:i:s');
            $update = $this->master_db->updateRecord('slider_img', $db, ['id' => $id]);
            if ($update > 0) {
                $results = ['status' => true, 'msg' => 'Updated successfully', 'csrf_token' => $this->security->get_csrf_hash() ];
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated successfully</div>');
            } else {
                $results = ['status' => false, 'msg' => 'Error in updating ', 'csrf_token' => $this->security->get_csrf_hash() ];
                echo json_encode($results);
            }
        } else {
            if (!empty($_FILES['sfile']['name'])) {
                $uploadPath = '../assets/sliders/';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'jpg|png|PNG|JPEG';
                $config['max_width'] = 1903;
                $config['max_height'] = 520;
                $ext = pathinfo($_FILES["sfile"]['name'], PATHINFO_EXTENSION);
                $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                $config['file_name'] = $new_name;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('sfile')) {
                    $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                    redirect(base_url() . 'bannersadd');
                } else {
                    $upload_data = $this->upload->data();
                    $db['image'] = 'assets/sliders/' . $upload_data['file_name'];
                }
            }
            $db['stitle'] = $stitle;
            $db['stagline'] = $stagline;
            $db['sliderlink'] = $link;
            $db['type'] = $type;
            $db['created_at'] = date('Y-m-d H:i:s');
            $in = $this->master_db->insertRecord('slider_img', $db);
            if ($in) {
                $result = ['status' => true, 'msg' => 'Inserted successfully', 'csrf_token' => $this->security->get_csrf_hash() ];
                echo json_encode($result);
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Inserted successfully</div>');
            } else {
                $result = ['status' => false, 'msg' => 'Error in inserting ', 'csrf_token' => $this->security->get_csrf_hash() ];
            }
        }
        redirect(base_url() . 'banners');
    }
    public function editslider() {
        $id = icchaDcrypt($_GET['id']);
        //echo $id;exit;
        $cities = $this->master_db->getRecords('slider_img', ['id' => $id], '*');
        //echo $this->db->last_query();exit;
        $this->data['city'] = $cities;
        $this->data['site_type'] = $this->master_db->getRecords('site_type',['id !='=>-1],'*');
        $this->load->view('masters/sliders/slideradd', $this->data);
    }
    /****** Area ******/
    public function area() {
        $this->load->view('masters/area/area', $this->data);
    }
    public function getarea() {
        $where = "where a.status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (c.cname like '%$val%') ";
            $where.= " or (a.areaname like '%$val%') ";
            $where.= " or (a.created_at like '%$val%') ";
        }
        $order_by_arr[] = "a.areaname";
        $order_by_arr[] = "";
        $order_by_arr[] = "a.id";
        $order_by_def = " order by a.id desc";
        $query = "select a.areaname,a.created_at,a.status,c.cname,a.id from area a left join  cities c on c.id = a.cid  " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $action.= '<a href=' . base_url() . "master/editarea?id=" . icchaEncrypt($r->id) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            $status = '';
            $type = "";
            if ((int)$r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", -1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Activate' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            // $action .= "<button title='View Detail' class='btns' onClick='popUp()'><i class='fas fa-eye' ></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $r->cname;
            $sub_array[] = $r->areaname;
            $sub_array[] = $r->id;
            //$sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> ".date('d M y',strtotime($r->created_at))."<br/>"."<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> ".date('h:i:s A',strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function areaadd() {
        $getcities = $this->master_db->getRecords('cities', ['status' => 0], 'id,cname', 'cname asc');
        $this->data['cities'] = $getcities;
        $this->data['type'] = "add";
        $this->load->view('masters/area/areaadd', $this->data);
    }
    public function setareaStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('area', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == - 1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('area', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('area', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    public function savearea() {
        //echo "<pre>";print_r($_POST);exit;
        $city = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('city', true))));
        $area = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('area', true))));
        $id = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('id', true))));
        $getArea = $this->master_db->getRecords('area',['areaname'=>$area],'*');
        if(count($getArea) >0) {
                $results = ['status' => false, 'msg' => 'Area already exists try another', 'csrf_token' => $this->security->get_csrf_hash() ];
                        echo json_encode($results);
        }else {
                     if (!empty($id)) {
                    $db['cid'] = $city;
                    $db['areaname'] = $area;
                    $db['modified_at'] = date('Y-m-d H:i:s');
                    $update = $this->master_db->updateRecord('area', $db, ['id' => $id]);
                    if ($update) {
                        $results = ['status' => true, 'msg' => 'Updated successfully', 'csrf_token' => $this->security->get_csrf_hash() ];
                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated successfully</div>');
                        echo json_encode($results);
                    } else {
                        $results = ['status' => false, 'msg' => 'Error in updating ', 'csrf_token' => $this->security->get_csrf_hash() ];
                        echo json_encode($results);
                    }
                } else {
                    if (!empty($city)) {
                        $db['cid'] = $city;
                        $db['areaname'] = $area;
                        $db['created_at'] = date('Y-m-d H:i:s');
                        $in = $this->master_db->insertRecord('area', $db);
                        if ($in) {
                            $result = ['status' => true, 'msg' => 'Inserted successfully', 'csrf_token' => $this->security->get_csrf_hash() ];
                            echo json_encode($result);
                            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Inserted successfully</div>');
                        } else {
                            $result = ['status' => false, 'msg' => 'Error in inserting ', 'csrf_token' => $this->security->get_csrf_hash() ];
                        }
                    } else {
                        $result = ['status' => false, 'msg' => 'Required fields are missing', 'csrf_token' => $this->security->get_csrf_hash() ];
                        echo json_encode($result);
                    }
                }  
        }
       
    }
    public function editarea() {
        $id = icchaDcrypt($_GET['id']);
        //echo $id;exit;
        $cities = $this->master_db->getRecords('cities', ['status' => 0], '*');
        $area = $this->master_db->getRecords('area', ['id' => $id], 'id,areaname,cid');
        //echo $this->db->last_query();exit;
        $this->data['cities'] = $cities;
        $this->data['type'] = "edit";
        $this->data['area'] = $area;
        $this->load->view('masters/area/areaadd', $this->data);
    }
    /*** Testimonials *******/
    public function testimonials() {
        $this->load->view('masters/testimonials/testimonials', $this->data);
    }
    public function testimonialsadd() {
        $this->load->view('masters/testimonials/testimonialsadd', $this->data);
    }
    public function testimonialssave() {
        // echo "<pre>";print_r($_POST);exit;
        $name = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('name', true))));
        $location = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('location', true))));
        $msg = trim($this->input->post('msg'));
        $id = $this->input->post('id');
        if (!empty($id)) {
            $getTestimonials = $this->master_db->getRecords('testimonials', ['id' => $id], 'image');
            $images = app_url() . $getTestimonials[0]->image;
            $db['name'] = $name;
            $db['location'] = $location;
            if (!empty($_FILES['file']['name'])) {
                //unlink("$images");
                $uploadPath = '../assets/testimonials/';
                $config['upload_path'] = $uploadPath;
                $config['allowed_types'] = 'jpg|png|PNG|JPEG';
                $ext = pathinfo($_FILES["file"]['name'], PATHINFO_EXTENSION);
                $new_name = "sqft9" . rand(11111, 99999) . '.' . $ext;
                $config['file_name'] = $new_name;
                $this->load->library('upload', $config);
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('file')) {
                    $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                    redirect('property/myproperty');
                } else {
                    $upload_data = $this->upload->data();
                    $db['image'] = 'assets/testimonials/' . $upload_data['file_name'];
                }
            }
            $db['tdesc'] = $msg;
            $db['modified_at'] = date('Y-m-d H:i:s');
            $update = $this->master_db->updateRecord('testimonials', $db, ['id' => $id]);
            if ($update) {
                $results = ['status' => true, 'msg' => 'Updated successfully'];
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated successfully</div>');
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in updating</div>');
            }
        } else {
            if (!empty($name) && !empty($location)) {
                $db['name'] = $name;
                $db['location'] = $location;
                if (!empty($_FILES['file']['name'])) {
                    $uploadPath = '../assets/testimonials/';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'jpg|png|PNG|JPEG';
                    $ext = pathinfo($_FILES["file"]['name'], PATHINFO_EXTENSION);
                    $new_name = "sqft9" . rand(11111, 99999) . '.' . $ext;
                    $config['file_name'] = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('file')) {
                        $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                    } else {
                        $upload_data = $this->upload->data();
                        $db['image'] = 'assets/testimonials/' . $upload_data['file_name'];
                    }
                }
                $db['tdesc'] = $msg;
                $db['status'] = 0;
                $db['created_at'] = date('Y-m-d H:i:s');
                $in = $this->master_db->insertRecord('testimonials', $db);
                if ($in) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Inserted successfully</div>');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in inserting</div>');
                }
            } else {
                $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Required fields are missing</div>');
            }
        }
        redirect(base_url() . 'testimonials');
    }
    public function getTestimonials() {
        $where = "where status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (name like '%$val%') ";
            $where.= " or (location like '%$val%') ";
            $where.= " or (tdesc like '%$val%') ";
        }
        $order_by_arr[] = "name";
        $order_by_arr[] = "";
        $order_by_arr[] = "id";
        $order_by_def = " order by id desc";
        $query = "select * from testimonials " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $action.= '<a href=' . base_url() . "master/edittestimonials?id=" . icchaEncrypt($r->id) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            $status = '';
            $type = "";
            if ((int)$r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", -1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Activate' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            $image = "<img src='" . app_url() . $r->image . "' style='width:80px'/>";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $image;
            $sub_array[] = $r->name;
            $sub_array[] = $r->location;
            $sub_array[] = $r->tdesc;
            //$sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> ".date('d M y',strtotime($r->created_at))."<br/>"."<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> ".date('h:i:s A',strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function edittestimonials() {
        $id = icchaDcrypt($_GET['id']);
        $this->data['category'] = $this->master_db->getRecords('testimonials', ['status !=' => - 1], 'id,name');
        //echo $id;exit;
        $getTestimonials = $this->master_db->getRecords('testimonials', ['id' => $id], '*');
        //echo $this->db->last_query();exit;
        $this->data['testimonials'] = $getTestimonials;
        $this->load->view('masters/testimonials/testimonialsadd', $this->data);
    }
    public function settestimonialStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('testimonials', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == - 1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('testimonials', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('testimonials', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    /*** Pincodes *******/
    public function pincodes() {
        $this->load->view('masters/pincodes/pincodes', $this->data);
    }
    public function pincodeadd() {
        $this->data['city'] = $this->master_db->getRecords('cities', ['status' => 0], 'id,cname');
        $this->data['type'] = $this->master_db->getRecords('site_type',['id !='=>-1],'*');
        $this->load->view('masters/pincodes/pincodesadd', $this->data);
    }
    public function getPincodes() {
        $where = "where p.status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (p.pincode like '%$val%') ";
            $where.= " or (c.cname like '%$val%') ";
        }
        $order_by_arr[] = "p.name";
        $order_by_arr[] = "";
        $order_by_arr[] = "p.id";
        $order_by_def = " order by id desc";
        $query = "select p.id,p.pincode,p.amount,p.cid,p.created_at,p.status,c.cname from pincodes p left join cities c on c.id = p.cid " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $action.= '<a href=' . base_url() . "master/editpincodes?id=" . icchaEncrypt($r->id) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            $status = '';
            $type = "";
            if ((int)$r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", -1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Activate' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            // $action .= "<button title='View Detail' class='btns' onClick='popUp()'><i class='fas fa-eye' ></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = "<label for='pinupd".$r->id."' style='cursor:pointer!important;'><input type='checkbox' name='pinupd[]' value='".$r->id."' id='pinupd".$r->id."' /> ".$r->pincode."</lable>";
            $sub_array[] = ucfirst($r->cname);
             $sub_array[] = $r->amount;
            // $sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> ".date('d M y',strtotime($r->created_at))."<br/>"."<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> ".date('h:i:s A',strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function pincodesave() {
        //echo "<pre>";print_r($_POST);exit;
        $pincode = $this->input->post('pincode');
        $id = $this->input->post('id');
        $cid =$this->input->post('cid');
        $amount = $this->input->post('amount');
        $type = $this->input->post('type');
        $getPincode = $this->master_db->getRecords('pincodes',['pincode'=>$pincode],'*');
        // if(count($getPincode) >0) {
        //     $results = ['status' => false, 'msg' => 'Pincode already exists try another', 'csrf_token' => $this->security->get_csrf_hash() ];
        //                 echo json_encode($results);
        // }else {
                  if (!empty($id)) {
                    $db['cid'] = $cid;
                    $db['pincode'] = $pincode;
                    $db['amount'] = $amount;
                    $db['site_type'] = $type;
                    $db['modified_at'] = date('Y-m-d H:i:s');
                    $update = $this->master_db->updateRecord('pincodes', $db, ['id' => $id]);
                    if ($update) {
                        $results = ['status' => true, 'msg' => 'Updated successfully', 'csrf_token' => $this->security->get_csrf_hash() ];
                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated successfully</div>');
                        echo json_encode($results);
                    } else {
                        $results = ['status' => false, 'msg' => 'Error in updating ', 'csrf_token' => $this->security->get_csrf_hash() ];
                        echo json_encode($results);
                    }
                } else {
                    if (!empty($pincode)) {
                        $db['cid'] = $cid;
                        $db['pincode'] = $pincode;
                        $db['amount'] = $amount;
                         $db['site_type'] = $type;
                        $db['created_at'] = date('Y-m-d H:i:s');
                        $in = $this->master_db->insertRecord('pincodes', $db);
                        if ($in) {
                            $result = ['status' => true, 'msg' => 'Inserted successfully', 'csrf_token' => $this->security->get_csrf_hash() ];
                            echo json_encode($result);
                            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Inserted successfully</div>');
                        } else {
                            $result = ['status' => false, 'msg' => 'Error in inserting ', 'csrf_token' => $this->security->get_csrf_hash() ];
                        }
                    } else {
                        $result = ['status' => false, 'msg' => 'Required fields are missing', 'csrf_token' => $this->security->get_csrf_hash() ];
                        echo json_encode($result);
                    }
                }
        //}
    }
    public function editpincodes() {
        $this->load->library('encrypt');
        $id = icchaDcrypt($_GET['id']);
        $this->data['city'] = $this->master_db->getRecords('cities', ['status' => 0], 'id,cname');
        //echo $id;exit;
        $getPackage = $this->master_db->getRecords('pincodes', ['id' => $id], '*');
        //echo $this->db->last_query();exit;
        $this->data['pincodes'] = $getPackage;
        $this->data['type'] = $this->master_db->getRecords('site_type',['id !='=>-1],'*');
        $this->load->view('masters/pincodes/pincodesadd', $this->data);
    }
    public function setpincodesStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('pincodes', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == - 1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('pincodes', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('pincodes', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    /****** FAQ ******/
    public function faq() {
        $this->load->view('masters/faq/faq', $this->data);
    }
    public function getFaq() {
        $where = "where status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (title like '%$val%') ";
            $where.= " or (fdesc like '%$val%') ";
        }
        $order_by_arr[] = "title";
        $order_by_arr[] = "";
        $order_by_arr[] = "id";
        $order_by_def = " order by id desc";
        $query = "select * from faq " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $action.= '<a href=' . base_url() . "master/faqedit?id=" . icchaEncrypt($r->id) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            $status = '';
            $type = "";
            if ((int)$r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", -1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Activate' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            // $action .= "<button title='View Detail' class='btns' onClick='popUp()'><i class='fas fa-eye' ></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $r->title;
            $sub_array[] = $r->fdesc;
            //$sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> ".date('d M y',strtotime($r->created_at))."<br/>"."<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> ".date('h:i:s A',strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function faqadd() {
        $this->load->view('masters/faq/faqadd', $this->data);
    }
    public function faqsave() {
        //echo "<pre>";print_r($_POST);exit;
        $title = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('title', true))));
        $id = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('id', true))));
        $content = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('content', true))));
        if (!empty($id)) {
            $db['title'] = $title;
            $db['fdesc'] = $content;
            $db['modified_at'] = date('Y-m-d H:i:s');
            $update = $this->master_db->updateRecord('faq', $db, ['id' => $id]);
            if ($update) {
                $results = ['status' => true, 'msg' => 'Updated successfully', 'csrf_token' => $this->security->get_csrf_hash() ];
                $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated successfully</div>');
                echo json_encode($results);
            } else {
                $results = ['status' => false, 'msg' => 'Error in updating ', 'csrf_token' => $this->security->get_csrf_hash() ];
                echo json_encode($results);
            }
        } else {
            if (!empty($title) && !empty($content)) {
                $db['title'] = $title;
                $db['fdesc'] = $content;
                $db['created_at'] = date('Y-m-d H:i:s');
                $in = $this->master_db->insertRecord('faq', $db);
                if ($in) {
                    $result = ['status' => true, 'msg' => 'Inserted successfully', 'csrf_token' => $this->security->get_csrf_hash() ];
                    echo json_encode($result);
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Inserted successfully</div>');
                } else {
                    $result = ['status' => false, 'msg' => 'Error in inserting ', 'csrf_token' => $this->security->get_csrf_hash() ];
                }
            } else {
                $result = ['status' => false, 'msg' => 'Required fields are missing', 'csrf_token' => $this->security->get_csrf_hash() ];
                echo json_encode($result);
            }
        }
    }
    public function faqedit() {
        $id = icchaDcrypt($_GET['id']);
        //echo $id;exit;
        $getPackage = $this->master_db->getRecords('faq', ['id' => $id], '*');
        //echo $this->db->last_query();exit;
        $this->data['faq'] = $getPackage;
        $this->load->view('masters/faq/faqadd', $this->data);
    }
    public function faqstatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('faq', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == - 1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('faq', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('faq', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    /****** colors ******/
    public function colors() {
        $this->load->view('masters/colors/colors', $this->data);
    }
    public function getColors() {
        $where = "where status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (name like '%$val%') ";
            $where.= " or (status like '%$val%') ";
        }
        $order_by_arr[] = "name";
        $order_by_arr[] = "";
        $order_by_arr[] = "co_id";
        $order_by_def = " order by co_id desc";
        $query = "select * from colors " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $action.= '<a href=' . base_url() . "master/colorsedit/" . icchaEncrypt($r->co_id) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            $status = '';
            $type = "";
            if ((int)$r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->co_id . ", -1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Activate' class='btn btn-warning' onclick='updateStatus(" . $r->co_id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->co_id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            // $action .= "<button title='View Detail' class='btns' onClick='popUp()'><i class='fas fa-eye' ></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $r->name;
            $sub_array[] = $r->co_id;
            //$sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> ".date('d M y',strtotime($r->created_at))."<br/>"."<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> ".date('h:i:s A',strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function colorsadd() {
        $this->load->view('masters/colors/colorsadd', $this->data);
    }
    public function colorssave() {
        //  echo "<pre>";print_r($_POST);exit;
        $title = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('cname', true))));
        $id = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('cid', true))));
        $ccode = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('ccode', true))));
        $getColor = $this->master_db->getRecords('colors',['name'=>$title],'*');
        // if(count($getColor) >0) {
        //      $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Color already exists try another</div>');
        //                 redirect(base_url() . 'colors');
        // }else {
                 if (!empty($id)) {
                    $db['name'] = $title;
                    $db['ccode'] = $ccode;
                    $db['modified_at'] = date('Y-m-d H:i:s');
                    $update = $this->master_db->updateRecord('colors', $db, ['co_id' => $id]);
                    if ($update > 0) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated successfully</div>');
                        redirect(base_url() . 'colors');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in updating</div>');
                        redirect(base_url() . 'colorsedit');
                    }
                } else {
                    if (!empty($title)) {
                        $db['name'] = $title;
                        $db['ccode'] = $ccode;
                        $db['created_at'] = date('Y-m-d H:i:s');
                        $in = $this->master_db->insertRecord('colors', $db);
                        if ($in > 0) {
                            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Inserted successfully</div>');
                            redirect(base_url() . 'colors');
                        } else {
                            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in inserting</div>');
                            redirect(base_url() . 'colorsadd');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Required fields is missing</div>');
                        redirect(base_url() . 'colorsadd');
                    }
                }
       // }
    }
    public function colorsedit() {
        $id = icchaDcrypt($this->uri->segment(3));
        //echo $id;exit;
        $getPackage = $this->master_db->getRecords('colors', ['co_id' => $id], '*');
        $this->data['color'] = $getPackage;
        $this->load->view('masters/colors/colorsedit', $this->data);
    }
    public function colorsstatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('colors', ['co_id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == - 1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('colors', $where_data, array('co_id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('colors', $where_data, array('co_id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    /* Coupons  */
    public function coupons() {
        $this->load->view('masters/coupons/coupons', $this->data);
    }
    public function getCouponlist() {
        $where = "where status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (type like '%$val%') ";
            $where.= " or (title like '%$val%') ";
            $where.= " or (from_date like '%$val%') ";
            $where.= " or (to_date like '%$val%') ";
            $where.= " or (discount like '%$val%') ";
        }
        $order_by_arr[] = "title";
        $order_by_arr[] = "";
        $order_by_arr[] = "id";
        $order_by_def = " order by id desc";
        $query = "select * from vouchers " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $type = "";
            if ($r->type == 0) {
                $type .= "Flat Discount";
            }
            else if($r->type ==1) {
                $type .= "Percentage Discount";
            }
            else if($r->type ==2) {
                $type .= "Individual Discount";
            }
            $action = '<a href=' . base_url() . "master/editcoupon/" . icchaEncrypt(($r->id)) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            if ($r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", 1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Active' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $type;
            $sub_array[] = ucfirst($r->title);
            $sub_array[] = date('d-m-Y',strtotime($r->from_date));
            $sub_array[] = date('d-m-Y',strtotime($r->to_date));
            $sub_array[] = $r->discount;
            $sub_array[] = $r->usage_limit;
            $sub_array[] = $r->min_amount;
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function couponsadd() {
        $this->load->view('masters/coupons/couponsadd', $this->data);
    }
    public function editcoupon() {
        $id = icchaDcrypt($this->uri->segment(3));
        //echo $id;exit;
        $this->data['coupons'] = $this->master_db->getRecords('vouchers', ['id' => $id], '*');
        //echo $this->db->last_query();exit;
        $this->load->view('masters/coupons/couponsedit', $this->data);
    }
    public function couponsave() {
       // echo "<pre>";print_r($_POST);exit;
        $this->load->library('form_validation');
        $type = trim($this->input->post('type'));
        $this->form_validation->set_rules('type','Coupon','required',['required'=>'Coupon type is required']);
        $this->form_validation->set_rules('ccode','Coupon Code','required|max_length[10]',['required'=>'Coupon Code is required','regex_match'=>'Only characters and numbers are allowed and spaces are not allowed','max_length'=>'Only 10 digits are allowed']);
        $this->form_validation->set_rules('fdate','From date','required',['required'=>'From date is required']);
        $this->form_validation->set_rules('tdate','To Date','required',['required'=>'To date is required']);
         $cid = trim($this->input->post('cid'));
        if(empty($cid)) {
            if($type ==2) {
            }else {
                // $this->form_validation->set_rules('usage','Usage','required',['required'=>'Usage value is required']);
            }
            $this->form_validation->set_rules('minval','Minimum value','required',['required'=>'Minimum value is required']);
        }
        $this->form_validation->set_rules('discount','Product Title','required|numeric',['required'=>'Coupon discount is required','numberic'=>'Only numbers are allowed']);
         if($this->form_validation->run() ==TRUE) {
            $ccode = trim($this->input->post('ccode'));
            $usage = trim($this->input->post('usage'));
            $minval = trim($this->input->post('minval'));
            $prefix = trim($this->input->post('prefix'));
            $noofvoucher = trim($this->input->post('noofvoucher'));
            $vouchercount = $this->input->post('vouchercount');
            $fdate = date('Y-m-d', strtotime($this->input->post('fdate')));
            $tdate = date('Y-m-d', strtotime($this->input->post('tdate')));
            $discount = trim($this->input->post('discount'));
            if(!empty($cid)) {
                      $up['type'] = $type;
                      $up['title'] = $ccode;
                      $up['from_date'] =$fdate;
                      $up['to_date'] = $tdate;
                      $up['discount'] = $discount;
                      if($type ==2) {
                        $up['usage_limit'] = 1;
                      }else {
                        $up['usage_limit'] = $usage;
                      }
                      
                      $up['min_amount'] = $minval;
                      $up['modified_at'] = date('Y-m-d H:i:s');
                      $update = $this->master_db->updateRecord('vouchers',$up,['id'=>$cid]);
                      $resul['status'] = true;
                      $resul['status'] = "Updated successfully";
                      $resul['csrf_token'] = $this->security->get_csrf_hash();
            }else {
                if(!empty($prefix) && !empty($noofvoucher) && is_array($vouchercount) && !empty($vouchercount[0])) 
                {
                    foreach ($vouchercount as $key => $v) {
                        if(!empty($v)) {
                            $getTitle = $this->master_db->getRecords('vouchers',['title'=>$v],'*');
                            if(count($getTitle)>0) {
                            }else {
                                 $in['type'] = $type;
                                $in['title'] = $v;
                                $in['from_date'] =$fdate;
                                $in['to_date'] =$tdate;
                                $in['discount'] = $discount;
                                $in['usage_limit'] = $usage;
                                $in['min_amount'] = $minval;
                                $in['created_at'] = date('Y-m-d H:i:s');
                                $res=$this->master_db->insertRecord('vouchers',$in);
                            }
                        }
                    }
                     $resul['status'] = true;
                     $resul['msg'] = "Inserted successfully";
                     $resul['csrf_token'] = $this->security->get_csrf_hash();
                }else {
                      $in['type'] = $type;
                      $in['title'] = $ccode;
                      $in['from_date'] =$fdate;
                      $in['to_date'] =$tdate;
                      $in['discount'] = $discount;
                      $in['usage_limit'] = $usage;
                      $in['min_amount'] = $minval;
                      $in['created_at'] = date('Y-m-d H:i:s');
                      $res=$this->master_db->insertRecord('vouchers',$in);
                    if($res >0) {
                      $resul['status'] = true;
                      $resul['msg'] = "Inserted successfully";
                      $resul['csrf_token'] = $this->security->get_csrf_hash();
                    }
                    else {
                        $resul['status'] = false;
                        $resul['msg'] = "Error in inserted";
                        $resul['csrf_token'] = $this->security->get_csrf_hash(); 
                    } 
                }
            }
         }else {
                $resul['formerror'] = false;
                $resul['type_error'] = form_error('type');
                $resul['ccode_error'] = form_error('ccode');
                $resul['fdate_error'] = form_error('fdate');
                $resul['tdate_error'] = form_error('tdate');
                $resul['usage_error'] = form_error('usage');
                $resul['minval_error'] = form_error('minval');
                $resul['dicount_error'] = form_error('discount');
                $resul['csrf_token'] = $this->security->get_csrf_hash();
         }
         echo json_encode($resul);
    }
    public function setcouponStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('vouchers', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('vouchers', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('vouchers', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    /****** SIZES ******/
    public function sizes() {
        $this->load->view('masters/sizes/sizes', $this->data);
    }
    public function getSize() {
        $where = "where status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (sname like '%$val%') ";
        }
        $order_by_arr[] = "sname";
        $order_by_arr[] = "";
        $order_by_arr[] = "s_id";
        $order_by_def = " order by s_id desc";
        $query = "select * from sizes " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $action.= '<a href=' . base_url() . "master/sizeedit/" . icchaEncrypt($r->s_id) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            $status = '';
            $type = "";
            if ((int)$r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->s_id . ", -1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Activate' class='btn btn-warning' onclick='updateStatus(" . $r->s_id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->s_id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            // $action .= "<button title='View Detail' class='btns' onClick='popUp()'><i class='fas fa-eye' ></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $r->sname;
            $sub_array[] = $r->s_id;
            //$sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> ".date('d M y',strtotime($r->created_at))."<br/>"."<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> ".date('h:i:s A',strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function sizeadd() {
        $this->load->view('masters/sizes/sizesadd', $this->data);
    }
    public function sizesave() {
        //  echo "<pre>";print_r($_POST);exit;
        $title = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('cname', true))));
        $id = trim(preg_replace('!\s+!', ' ', html_escape($this->input->post('cid', true))));
        $getSize = $this->master_db->getRecords('sizes',['sname'=>$title],'*');
        //echo $this->db->last_query();exit;
        if(count($getSize) >0) {
           $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Size already exists try another</div>');
                        redirect(base_url() . 'sizes'); 
        }else {
                if (!empty($id)) {
                    $db['sname'] = $title;
                    $db['modified_at'] = date('Y-m-d H:i:s');
                    $update = $this->master_db->updateRecord('sizes', $db, ['s_id' => $id]);
                    if ($update > 0) {
                        $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated successfully</div>');
                        redirect(base_url() . 'sizes');
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in updating</div>');
                        redirect(base_url() . 'sizeedit');
                    }
                } else {
                    if (!empty($title)) {
                        $db['sname'] = $title;
                        $db['created_at'] = date('Y-m-d H:i:s');
                        $in = $this->master_db->insertRecord('sizes', $db);
                        if ($in > 0) {
                            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Inserted successfully</div>');
                            redirect(base_url() . 'sizes');
                        } else {
                            $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in inserting</div>');
                            redirect(base_url() . 'sizesadd');
                        }
                    } else {
                        $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Required fields is missing</div>');
                        redirect(base_url() . 'sizesadd');
                    }
                }
        }
        
    }
    public function sizeedit() {
        //echo $this->uri->segment(3);exit;
        $id = icchaDcrypt($this->uri->segment(3));
        //echo $id;exit;
        $getPackage = $this->master_db->getRecords('sizes', ['s_id' => $id], '*');
        //echo $this->db->last_query();exit;
        $this->data['sizes'] = $getPackage;
        $this->load->view('masters/sizes/sizesedit', $this->data);
    }
    public function sizetatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('sizes', ['s_id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == - 1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('sizes', $where_data, array('s_id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('sizes', $where_data, array('s_id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    /**** About *******/
    public function about() {
        $this->data['about'] = $this->master_db->getRecords('aboutus', ['id' => 1], '*');
        $this->load->view('homepage/about', $this->data);
    }
    public function aboutussave() {
        $id = 1;
        $about = trim($this->input->post('content'));
        $db['adesc'] = $about;
        $db['modified_at'] = date('Y-m-d H:i:s');
        $update = $this->master_db->updateRecord('aboutus', $db, array('id' => $id));
        if ($update) {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Updated successfully</div>');
            redirect('aboutus');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Error in updating</div>');
            redirect('aboutus');
        }
    }
    /**** Terms *******/
    public function terms() {
        $this->data['terms'] = $this->master_db->getRecords('terms', ['id' => 1], '*');
        $this->load->view('homepage/terms-condition', $this->data);
    }
    public function termssave() {
        $id = 1;
        $about = trim($this->input->post('content'));
        $db['tdesc'] = $about;
        $db['modified_at'] = date('Y-m-d H:i:s');
        $update = $this->master_db->updateRecord('terms', $db, array('id' => $id));
        if ($update) {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Updated successfully</div>');
            redirect('terms');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Error in updating</div>');
            redirect('terms');
        }
    }
    /**** Privacy *******/
    public function privacy() {
        $this->data['privacy'] = $this->master_db->getRecords('privacypolicy', ['id' => 1], '*');
        $this->load->view('homepage/privacy-policy', $this->data);
    }
    public function privacysave() {
        $id = 1;
        $about = trim($this->input->post('content'));
        $db['pdesc'] = $about;
        $db['modified_at'] = date('Y-m-d H:i:s');
        $update = $this->master_db->updateRecord('privacypolicy', $db, array('id' => $id));
        if ($update) {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Updated successfully</div>');
            redirect('privacy');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Error in updating</div>');
            redirect('privacy');
        }
    }
    /**** Cancellation *******/
    public function cancellationpolicy() {
        $this->data['cancellation'] = $this->master_db->getRecords('cancellation', ['id' => 1], '*');
        $this->load->view('homepage/cancellation-policy', $this->data);
    }
    public function cancellationsave() {
        $id = 1;
        $about = trim($this->input->post('content'));
        $db['cdesc'] = $about;
        $db['modified_at'] = date('Y-m-d H:i:s');
        $update = $this->master_db->updateRecord('cancellation', $db, array('id' => $id));
        if ($update) {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Updated successfully</div>');
            redirect('cancellationpolicy');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Error in updating</div>');
            redirect('cancellationpolicy');
        }
    }
    /**** Shipping Policy *******/
    public function shippingpolicy() {
        $this->data['shippingpolicy'] = $this->master_db->getRecords('shippingpolicy', ['id' => 1], '*');
        $this->load->view('homepage/shipping-policy', $this->data);
    }
    public function shippingsave() {
        $id = 1;
        $about = trim($this->input->post('content'));
        $db['shippolicy'] = $about;
        $db['modified_at'] = date('Y-m-d H:i:s');
        $update = $this->master_db->updateRecord('shippingpolicy', $db, array('id' => $id));
        if ($update) {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Updated successfully</div>');
            redirect('shippingpolicy');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Error in updating</div>');
            redirect('shippingpolicy');
        }
    }
    /******** Return policy *******/
    public function returnpolicy() {
        $this->data['return'] = $this->master_db->getRecords('returnpolicy', ['id' => 1], '*');
        $this->load->view('homepage/return-policy', $this->data);
    }
    public function returnpolicysave() {
        $id = 1;
        $about = trim($this->input->post('content'));
        $db['rdesp'] = $about;
        $db['created_at'] = date('Y-m-d H:i:s');
        $update = $this->master_db->updateRecord('returnpolicy', $db, array('id' => $id));
        if ($update) {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Updated successfully</div>');
            redirect('master/returnpolicy');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Error in updating</div>');
            redirect('master/returnpolicy');
        }
    }
    /**** Brochure *******/
    public function brochure() {
        $this->data['brochure'] = $this->master_db->getRecords('brochure', ['id' => 1], '*');
        $this->load->view('homepage/brochure', $this->data);
    }
    public function brochuresave() {
        $id = 1;
        if (!empty($_FILES['file']['name'])) {
            //unlink("$images");
            $uploadPath = '../assets/brochure/';
            $config['upload_path'] = $uploadPath;
            $config['allowed_types'] = 'pdf';
            $config['max_size'] = 2048;
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            if (!$this->upload->do_upload('file')) {
                $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                redirect('brochure');
            } else {
                $upload_data = $this->upload->data();
                $db['pdf'] = 'assets/brochure/' . $upload_data['file_name'];
            }
        }
        $update = $this->master_db->updateRecord('brochure', $db, array('id' => $id));
        if ($update) {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Updated successfully</div>');
            redirect('brochure');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Error in updating</div>');
            redirect('brochure');
        }
    }
    /**** Contact us *******/
    public function contactus() {
        $this->data['contact'] = $this->master_db->getRecords('contactus', ['id' => 1], '*');
        $this->load->view('homepage/contact-us', $this->data);
    }
    public function contactsave() {
        $id = 1;
        $email = trim($this->input->post('email'));
        $phone = trim($this->input->post('phone'));
        $address = trim($this->input->post('address'));
        $db['email'] = $email;
        $db['phone'] = $phone;
        $db['address'] = $address;
        $db['modified_at'] = date('Y-m-d H:i:s');
        $update = $this->master_db->updateRecord('contactus', $db, array('id' => $id));
        if ($update) {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Updated successfully</div>');
            redirect('contactus');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Error in updating</div>');
            redirect('contactus');
        }
    }
    /**** Social Links  *******/
    public function sociallinks() {
        $this->data['sociallinks'] = $this->master_db->getRecords('sociallinks', ['id' => 1], '*');
        $this->load->view('homepage/sociallinks', $this->data);
    }
    public function sociallinksave() {
        $id = 1;
        $facebook = trim($this->input->post('facebook'));
        $twitter = trim($this->input->post('twitter'));
        $linkedin = trim($this->input->post('linkedin'));
        $instagram = trim($this->input->post('instagram'));
        $youtube = trim($this->input->post('youtube'));
        $db['facebook'] = $facebook;
        $db['twitter'] = $twitter;
        $db['linkedin'] = $linkedin;
        $db['instagram'] = $instagram;
        $db['modified_at'] = date('Y-m-d H:i:s');
        $db['youtube'] = $youtube;
        $update = $this->master_db->updateRecord('sociallinks', $db, array('id' => $id));
        if ($update) {
            $this->session->set_flashdata('message', '<div class="alert alert-success">Updated successfully</div>');
            redirect('sociallinks');
        } else {
            $this->session->set_flashdata('message', '<div class="alert alert-danger">Error in updating</div>');
            redirect('sociallinks');
        }
    }
    /****** Newsletter *****/
    public function newsletter() {
        $this->load->view('homepage/newsletter', $this->data);
    }
    public function getNewletter() {
        $where = "where status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (email like '%$val%') ";
        }
        $order_by_arr[] = "email";
        $order_by_arr[] = "";
        $order_by_arr[] = "id";
        $order_by_def = " order by id desc";
        $query = "select * from newsletter " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $sub_array[] = $i++;
            $sub_array[] = $r->email;
            $sub_array[] = date('d-m-Y', strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    /******* Products ***************/
    public function products() {
      $this->load->view('masters/products/products',$this->data);
    }
     public function productsadd() {
      $this->data['category'] = $this->master_db->getRecords('category',['status'=>0],'id,cname');
      $this->data['subcategory'] = $this->master_db->getRecords('subcategory',['status'=>0],'id,sname');
      $this->data['subsubcategory'] = $this->master_db->getRecords('subsubcategory',['status'=>0],'id,ssname');
      $this->data['brand'] = $this->master_db->getRecords('brand',['status'=>0],'id,name');
      $this->data['sizes'] = $this->master_db->getRecords('sizes',['status'=>0],'s_id as id,sname');
      $this->data['colors'] = $this->master_db->getRecords('colors',['status'=>0],'co_id as id,name');
      $this->data['site_type'] = $this->master_db->getRecords('site_type',['id !='=>-1],'*');
      $this->load->view('masters/products/productsadd',$this->data);
    }
    public function getProducts() {
         $where = "where p.status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (p.ptitle like '%$val%') ";
            $where.= " or (p.pcode like '%$val%') ";
            $where.= " or (ps.stock like '%$val%') ";
        }
        $order_by_arr[] = "p.id";
        $order_by_arr[] = "";
        $order_by_arr[] = "p.ptitle";
        $order_by_def = " order by p.id desc";
        $query = "select c.cname,p.ptitle as title,p.newarrivals,p.bestselling,p.featured,p.featuredimg,p.id,p.pcode,p.status,pi.p_image as img,ps.stock from products p left join category c on p.cat_id = c.id  left join product_images pi on  p.id =pi.pid left join product_size ps on p.id =ps.pid  ".$where." group by ps.pid";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $action.= '<a href=' . base_url() . "master/productsedit/" . icchaEncrypt($r->id) . "" . '  class="btn btn-primary" title="Edit Details" style="margin-bottom:10px"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            $status = '';$image ="";
            if(!empty($r->featuredimg)) {
                $image .="<img src='".app_url().$r->featuredimg."' style='width:80px' />";
            }
            $type = "";
            if ((int)$r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", -1)' style='margin-bottom:10px'><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Activate' class='btn btn-warning' style='margin-bottom:10px' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' style='margin-bottom:10px' onclick='updateStatus(" . $r->id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
             $action.= '<a href='.base_url()."master/productViewdetails/".icchaEncrypt($r->id)."".'  class="btn btn-success" style="margin-bottom:10px" title="View product details"><i class="fas fa-eye"></i></a>&nbsp;';
             if ((int)$r->newarrivals == 1) {
                $action.= "<button title='Show in new arrivals' style='margin-bottom:10px;padding-right: 12px;padding-left:12px;' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 3)' >N</button>&nbsp;";
             }else {
                $action.= "<button title='Dont show in new arrivals' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 4)' style='background:#e08e0b!important;color:#fff!important;border-color:#e08e0b!important;padding-right: 12px;padding-left:12px;'>N</button>&nbsp;";
             }
             if ((int)$r->bestselling == 1) {
                $action.= "<button title='Show in best seller' style='margin-bottom:10px;color:#000;padding-right: 12px;padding-left:12px;' class='btn btn-yellow' onclick='updateStatus(" . $r->id . ", 5)'>B</button>&nbsp;";
             }else {
                $action.= "<button title='Dont show in best seller' style='background:#772856!important;color:#fff!important;border-color:#772856!important;padding-right: 12px;padding-left:12px;' class='btn btn-yellow' onclick='updateStatus(" . $r->id . ", 6)'>B</button>&nbsp;";
             }
             if ((int)$r->featured == 1) {
                $action.= "<button title='Show in featured image' style='margin-bottom:10px;color:#000;padding-right: 12px;padding-left:12px;' class='btn btn-red' onclick='updateStatus(" . $r->id . ", 7)'>F</button>&nbsp;";
             }else {
                $action.= "<button title='Dont show in featured image' style='background:#8A4A0B!important;color:#fff!important;border-color:#8A4A0B!important;padding-right: 12px;padding-left:12px;' class='btn btn-yellow' onclick='updateStatus(" . $r->id . ", 8)'>F</button>&nbsp;";
             }
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $image;
            $sub_array[] = $r->title;
            $sub_array[] = $r->pcode;
            $sub_array[] = $r->stock;
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function productsedit() {
          $this->data['category'] = $this->master_db->getRecords('category',['status'=>0],'id,cname');
          $this->data['subcategory'] = $this->master_db->getRecords('subcategory',['status'=>0],'id,sname');
          $this->data['subsubcategory'] = $this->master_db->getRecords('subsubcategory',['status'=>0],'id,ssname');
          $this->data['brand'] = $this->master_db->getRecords('brand',['status'=>0],'id,name');
          $this->data['sizes'] = $this->master_db->getRecords('sizes',['status'=>0],'s_id as id,sname');
          $this->data['colors'] = $this->master_db->getRecords('colors',['status'=>0],'co_id as id,name');
          $id =  icchaDcrypt($this->uri->segment(3));
          $this->data['products'] = $this->master_db->getRecords('products',['id'=>$id],'*');
          $this->data['product_img'] = $this->master_db->getRecords('product_images',['pid'=>$id],'*');
          $this->data['producttype'] = $this->master_db->getRecords('product_site_type',['pid'=>$id],'*');
          $this->data['site_type'] = $this->master_db->getRecords('site_type',['id !='=>-1],'*');
          $this->data['product_size'] = $this->master_db->getRecords('product_size',['pid'=>$id],'*');
          $this->load->view('masters/products/productsedit',$this->data);
    }
    public function productseditdata() {
       //echo "<pre>";print_r($_POST);print_r($_FILES);exit;
         $this->load->library('form_validation');
        $this->form_validation->set_rules('category','Category','required',['required'=>'Category is required']);
        $this->form_validation->set_rules('pdesc','Product Description','required',['required'=>'Product description is required']);
        $this->form_validation->set_rules('subcategory','Subcategory','required',['required'=>'Subcategory is required']);
        $this->form_validation->set_rules('ptitle','Product Title','required|regex_match[/^([a-z0-9 ])+$/i]|max_length[100]',['max_length'=>'Only 100 characters are allowed','regex_match'=>'Only characters are allowed','required'=>'Product title is required']);
        $this->form_validation->set_rules('pcode','Product Code','required|regex_match[/^([A-Za-z0-9 ])+$/i]|max_length[30]',['max_length'=>'Only 30 characters are allowed','regex_match'=>'Only characters and numbers are allowed','required'=>'Product code is required']);
        $this->form_validation->set_rules('sell[0]','Upload','required|numeric',['required'=>'Sellprice is required','numeric'=>'Only numbers are allowed']);
        $this->form_validation->set_rules('psize[0]','Upload','required',['required'=>'Please select size']);
        $this->form_validation->set_rules('ylink','Youtube ','valid_url',['valid_url'=>'Please enter valid url']);
        $this->form_validation->set_rules('discount','Discount ','numeric',['numeric'=>'Only numbers are allowed']);
        //$this->form_validation->set_rules('tax','Tax ','',['numeric'=>'Only numbers are allowed']);
        $this->form_validation->set_rules('mno','Modal Number','regex_match[/^([A-Za-z0-9])+$/i]|max_length[30]',['max_length'=>'Only 30 characters are allowed','regex_match'=>'Only characters and numbers are allowed and spaces not allowed']);
        $this->form_validation->set_rules('mtitle','Meta Title','regex_match[/^([A-Za-z0-9- ])+$/i]|max_length[60]',['max_length'=>'Only 60 characters are allowed','regex_match'=>'Only characters and numbers and hyphens are allowed']);
        $this->form_validation->set_rules('mdesc','Meta Description','regex_match[/^([A-Za-z0-9- ])+$/i]|max_length[160]',['max_length'=>'Only 160 characters are allowed','regex_match'=>'Only characters and numbers and hyphens are allowed']);
        $this->form_validation->set_rules('mkeywords','Meta Keywords','regex_match[/^([A-Za-z0-9, ])+$/i]|max_length[160]',['max_length'=>'Only 160 characters are allowed','regex_match'=>'Only characters and numbers and comma are allowed']);
        if($this->form_validation->run() ==TRUE) {
            $category = $this->input->post('category');
            $subcategory = $this->input->post('subcategory');
            $subsubcategory = $this->input->post('subsubcategory');
            $brand = $this->input->post('brand');
            $ptitle = $this->input->post('ptitle');
            $pcode = $this->input->post('pcode');
            $discount = $this->input->post('discount');
            $tax = $this->input->post('tax');
            $length = $this->input->post('length');
            $breadth = $this->input->post('breadth');
            $weight = $this->input->post('weight');
            $orderno = $this->input->post('orderno');
            $tax = $this->input->post('tax');
            $height = $this->input->post('height');
            $pcode = $this->input->post('pcode');
            $youtube = $this->input->post('ylink');
            $mno = $this->input->post('mno');
            $pdesc = $this->input->post('pdesc');
            $pspec = $this->input->post('pspec');
            $psize = $this->input->post('psize');
            $colors = $this->input->post('colors');
            $sell = $this->input->post('sell');
            $mrp = $this->input->post('mrp');
            $stock = $this->input->post('stock');
            $mtitle = $this->input->post('mtitle');
            $mdesc = $this->input->post('mdesc');
            $mkeywords = $this->input->post('mkeywords');
            $pid = $this->input->post('pid');
            $sizeid = $this->input->post('sizeid');
            $imgid = $this->input->post('imgid');
            $site_type = $this->input->post('site_type');
            $siteids = $this->input->post('siteids');
            $sizeIds = [];$imageIds = [];$sitetypeid = [];
            $getSizes = $this->master_db->getRecords('product_size',['pid'=>$pid],'*');
            $getImages = $this->master_db->getRecords('product_images',['pid'=>$pid],'*');
            $getStype = $this->master_db->getRecords('product_site_type',['pid'=>$pid],'*');
            foreach ($getSizes as $size) {
                $sizeIds[] = $size->pro_size;
            }
            foreach ($getImages as $image) {
                $imageIds[] = $image->id;
            }
            foreach ($getStype as $so) {
                $sitetypeid[] = $so->id;
            }
            if(is_array($site_type) && !empty($site_type)) {
                foreach ($site_type as $key => $sitearr) {
                    if(!in_array($sitearr, $sitetypeid)) {
                        $sitelist['pid'] = $pid;
                        $sitelist['type'] = $sitearr;
                        $this->master_db->insertRecord('product_site_type',$sitelist);
                    }
                }
            }
            if(is_array($sitetypeid) && !empty($sitetypeid)) {
                foreach ($sitetypeid as $key => $sup) {
                    if(!in_array($sup, $site_type)) {
                        $this->master_db->deleterecord('product_site_type',['id'=>$sup]);
                    }
                }
            }
            $youtubeid = "";
            $uploaddir = '../assets/products/';
            $arry = array("PNG","jpg","png","JPEG","jpeg","JPG");
            if(!empty($youtube)) {
                $ex = explode("v=", $youtube);
                $youtubeid .= $ex[1];
            }
            if(!empty($category) && !empty($subcategory) && !empty($ptitle)) {
                $update['cat_id'] = $category;
                $update['subcat_id'] = $subcategory;
                $update['sub_sub_id'] = $subsubcategory;
                $update['brand_id'] = $brand;
                $update['ptitle'] = $ptitle;
              
                $update['pcode'] = $pcode;
                $update['overview'] = $pdesc;
                $update['pspec'] = $pspec;
                $update['meta_title'] = $mtitle;
                $update['meta_keywords'] = $mkeywords;
                $update['meta_description'] = $mdesc;
                 if (!empty($_FILES['fimage']['name'])) {
                        $uploadPath = '../assets/products/';
                        $config['upload_path'] = $uploadPath;
                        $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                        $ext = pathinfo($_FILES["fimage"]['name'], PATHINFO_EXTENSION);
                        $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                        $config['file_name'] = $new_name;
                        // $config['max_width'] = 200;
                        // $config['max_height'] = 240;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('fimage')) {
                            $array1 = ['status'=>false,'msg'=>"Featured Image : ".$this->upload->display_errors(),'csrf_token'=> $this->security->get_csrf_hash()];
                            echo json_encode($array1);exit;
                        } else {
                            $upload_data1 = $this->upload->data();
                            $update['featuredimg'] = 'assets/products/' . $upload_data1['file_name'];
                        }
                    }
                    $update['modalno'] = $mno;
                    $update['tax'] = $tax;
                $update['youtubelink'] = $youtubeid;
                $update['modified_at'] = date('Y-m-d h:i:s');
                $update['orderno'] = $orderno;
               
                $products = $this->master_db->updateRecord('products',$update,['id'=>$pid]);
              
                    if(count($sizeid) >0) {
                         foreach($sizeid as $key => $sis) {
                            if(in_array($sis, $sizeIds)) {
                                $db['pid'] = $pid;
                                $db['sid'] = $psize[$key];
                                $db['mrp'] = $mrp[$key];
                                $db['selling_price'] = $sell[$key];
                                $db['stock'] = $stock[$key];
                                
                                $sizeimg = $this->master_db->updateRecord('product_size',$db,['pro_size'=>$sis]);
                            }else {
                                //echo "inserted";
                                $db['pid'] = $pid;
                                $db['sid'] = $psize[$key];
                                $db['mrp'] = $mrp[$key];
                                $db['selling_price'] = $sell[$key];
                                $db['stock'] = $stock[$key];
                                $sizeimg = $this->master_db->insertRecord('product_size',$db);
                            }
                        }
                    }
                    if(count($imgid) >0) {
                        foreach ($imgid as $i => $image) {
                            if(in_array($image, $imageIds)) {
                                 if(!empty($_FILES['pfile']['name'][$i])){  
                                            $_FILES['photo']['name'] = $_FILES['pfile']['name'][$i];  
                                            $_FILES['photo']['type'] = $_FILES['pfile']['type'][$i];  
                                            $_FILES['photo']['tmp_name'] = $_FILES['pfile']['tmp_name'][$i];  
                                            $_FILES['photo']['error'] = $_FILES['pfile']['error'][$i];  
                                            $_FILES['photo']['size'] = $_FILES['pfile']['size'][$i];  
                                            $config['upload_path'] = '../assets/products/';   
                                            $config['allowed_types'] = 'jpg|jpeg|png|JPEG|JPG';  
                                           
                                            $ext = pathinfo($_FILES["pfile"]['name'][$i], PATHINFO_EXTENSION);
                                            $new_name = "SWARNA".rand(11111,99999).'.'.$ext; 
                                            $config['file_name'] =  $new_name;
                                            $this->load->library('upload',$config);  
                                            $this->upload->initialize($config);  
                                            if(!$this->upload->do_upload('photo')){  
                                                $array1 = ['status'=>false,'msg'=>$this->upload->display_errors(),'csrf_token'=> $this->security->get_csrf_hash()];
                                                echo json_encode($array1);exit;
                                            }else {
                                                $uploadData = $this->upload->data();  
                                                $filename = 'assets/products/'.$uploadData['file_name'];
                                                $dbs['pid'] = $pid;
                                                $dbs['p_image'] = $filename;
                                                $this->master_db->updateRecord('product_images',$dbs,['id'=>$image]);
                                            }
                                         } 
                            }else {
                                       if(!empty($_FILES['pfile']['name'][$i])){  
                                            $_FILES['photos']['name'] = $_FILES['pfile']['name'][$i];  
                                            $_FILES['photos']['type'] = $_FILES['pfile']['type'][$i];  
                                            $_FILES['photos']['tmp_name'] = $_FILES['pfile']['tmp_name'][$i];  
                                            $_FILES['photos']['error'] = $_FILES['pfile']['error'][$i];  
                                            $_FILES['photos']['size'] = $_FILES['pfile']['size'][$i];  
                                            $config['upload_path'] = '../assets/products/';   
                                            $config['allowed_types'] = 'jpg|jpeg|png|JPEG|JPG';  
                                           
                                            $ext = pathinfo($_FILES["pfile"]['name'][$i], PATHINFO_EXTENSION);
                                            $new_name = "SWARNA".rand(11111,99999).'.'.$ext; 
                                            $config['file_name'] =  $new_name;
                                            $this->load->library('upload',$config);  
                                            $this->upload->initialize($config);  
                                            if(!$this->upload->do_upload('photos')){  
                                                $array1 = ['status'=>false,'msg'=>$this->upload->display_errors(),'csrf_token'=> $this->security->get_csrf_hash()];
                                                echo json_encode($array1);exit;
                                            }else {
                                                $uploadData = $this->upload->data();  
                                                $filename = 'assets/products/'.$uploadData['file_name'];
                                                $dbs['pid'] =$pid;
                                                $dbs['p_image'] = $filename;
                                                $this->master_db->insertRecord('product_images',$dbs);
                                            }
                                         }    
                            }
                            
                        }
                    }
                    $array = ['status'=>true,'msg'=>'Updated successfully','csrf_token'=> $this->security->get_csrf_hash()];
                
            }else {
                $array = ['status'=>false,'msg'=>'Required fields is missing','csrf_token'=> $this->security->get_csrf_hash()];
            }        }else {
          $array = array(
            'formerror'   => false,
            'cat_error' => form_error('category'),
            'sub_error' => form_error('subcategory'),
            'subsub_error' => form_error('subsubcategory'),
            'pro_error' => form_error('ptitle'),
            'pdesc_error' => form_error('pdesc'),
            'pcode_error' => form_error('pcode'),
            'sell_error' => form_error('sell[0]'),
            'size_error' => form_error('psize[0]'),
            'discount_error' => form_error('discount'),
            'tax_error' => form_error('tax'),
            'ylink_error' => form_error('ylink'),
            'mno_error' => form_error('mno'),
            'mtitle_error' => form_error('mtitle'),
            'mdesc_error' => form_error('mdesc'),
            'mkeywords_error' => form_error('mkeywords'),
            'csrf_token'=> $this->security->get_csrf_hash()
           );
        }
        echo json_encode($array);
    }
    public function deleteSizes() {
        $id = icchaDcrypt($this->input->post('id'));
        $del = $this->master_db->deleterecord('product_size',['pro_size'=>$id]);
         echo json_encode(['status'=>true]);
    }
    public function deleteImages() {
        $id = icchaDcrypt($this->input->post('id'));
        $del = $this->master_db->deleterecord('product_images',['id'=>$id]);
         echo json_encode(['status'=>true]);
    }
   public function productssave() {
      // echo "<pre>";print_r($_POST);print_r($_FILES);exit;
        $this->load->library('form_validation');
        if(empty($_FILES['pfile']['name'][0])) {
            $this->form_validation->set_rules('pfile[]','Upload','required',['required'=>'Product image is required']);
        }
        $this->form_validation->set_rules('category','Category','required',['required'=>'Category is required']);
        $this->form_validation->set_rules('pdesc','Product Description','required',['required'=>'Product description is required']);
        $this->form_validation->set_rules('subcategory','Subcategory','required',['required'=>'Subcategory is required']);
        $this->form_validation->set_rules('ptitle','Product Title','required|regex_match[/^([a-z0-9 ])+$/i]|max_length[100]',['max_length'=>'Only 100 characters are allowed','regex_match'=>'Only characters are allowed','required'=>'Product title is required']);
        $this->form_validation->set_rules('pcode','Product Code','required|regex_match[/^([A-Za-z0-9 ])+$/i]|max_length[30]',['max_length'=>'Only 30 characters are allowed','regex_match'=>'Only characters and numbers are allowed','required'=>'Product code is required']);
         $this->form_validation->set_rules('mno','Modal Number','regex_match[/^([A-Za-z0-9])+$/i]|max_length[30]',['max_length'=>'Only 30 characters are allowed','regex_match'=>'Only characters and numbers are allowed and spaces not allowed']);
        $this->form_validation->set_rules('sell[0]','Upload','required|numeric',['required'=>'Sellprice is required','numeric'=>'Only numbers are allowed']);
        $this->form_validation->set_rules('size[0]','Upload','required',['required'=>'Please select size']);
        $this->form_validation->set_rules('ylink','Youtube ','valid_url',['valid_url'=>'Please enter valid url']);
        // $this->form_validation->set_rules('discount','Discount ','numberic',['numberic'=>'Only numbers are allowed']);
        //$this->form_validation->set_rules('tax','Tax ','numberic',['numberic'=>'Only numbers are allowed']);
        $this->form_validation->set_rules('mtitle','Meta Title','regex_match[/^([A-Za-z0-9- ])+$/i]|max_length[60]',['max_length'=>'Only 60 characters are allowed','regex_match'=>'Only characters and numbers and hyphens are allowed']);
        $this->form_validation->set_rules('mdesc','Meta Description','regex_match[/^([A-Za-z0-9- ])+$/i]|max_length[160]',['max_length'=>'Only 160 characters are allowed','regex_match'=>'Only characters and numbers and hyphens are allowed']);
        $this->form_validation->set_rules('mkeywords','Meta Keywords','regex_match[/^([A-Za-z0-9, ])+$/i]|max_length[160]',['max_length'=>'Only 160 characters are allowed','regex_match'=>'Only characters and numbers and comma are allowed']);
         if(empty($_FILES['fimage']['name'][0])) {
            $this->form_validation->set_rules('fimage','Featured Image ','required',['required'=>'Please upload featured image']);       
        }
        if($this->form_validation->run() ==TRUE) {
            $category = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('category', true))));
            $subcategory = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('subcategory', true))));
            $subsubcategory = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('subsubcategory', true))));
            $brand = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('brand', true))));
            $ptitle = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('ptitle', true))));
            $pcode = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('pcode', true))));
            $youtube = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('ylink', true))));
            $mno = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('mno', true))));
            $pdesc = $this->input->post('pdesc');
            $pspec = $this->input->post('pspec');
            $size = $this->input->post('size');
            $orderno = $this->input->post('orderno');
            $tax = $this->input->post('tax');
            $sell = $this->input->post('sell');
            $mrp = $this->input->post('mrp');
            $stock = $this->input->post('stock');
            $mtitle = $this->input->post('mtitle');
            $mdesc = $this->input->post('mdesc');
            $site_type = $this->input->post('site_type');
            $mkeywords = $this->input->post('mkeywords');
            $youtubeid = "";
            $uploaddir = '../assets/products/';
            $arry = array("PNG","jpg","png","JPEG","jpeg","JPG");
            if(!empty($youtube)) {
                $ex = explode("v=", $youtube);
                $youtubeid .= $ex[1];
            }
            if(!empty($category) && !empty($subcategory) && !empty($ptitle) && !empty($pcode)) {
                $getProducts = $this->master_db->getRecords('products',['ptitle'=>$ptitle],'*');
                $getPcode = $this->master_db->getRecords('products',['pcode'=>$pcode],'*');
                $getPmodal = $this->master_db->getRecords('products',['modalno'=>$mno],'*');
                 $insert['cat_id'] = $category;
                    $insert['subcat_id'] = $subcategory;
                    $insert['sub_sub_id'] = $subsubcategory;
                    $insert['brand_id'] = $brand;
                    $insert['ptitle'] = $ptitle;
                    $insert['page_url'] =$this->master_db->create_unique_slug($ptitle,'products','page_url');
                    $insert['pcode'] = $pcode;
                    $insert['overview'] = $pdesc;
                    $insert['pspec'] = $pspec;
                    $insert['meta_title'] = $mtitle;
                    $insert['meta_keywords'] = $mkeywords;
                    $insert['meta_description'] = $mdesc;
                    if (!empty($_FILES['fimage']['name'])) {
                        $uploadPath = '../assets/products/';
                        $config['upload_path'] = $uploadPath;
                        $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                        // $config['max_width'] = 200;
                        // $config['max_height'] = 240;
                        $ext = pathinfo($_FILES["fimage"]['name'], PATHINFO_EXTENSION);
                        $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                        $config['file_name'] = $new_name;
                        $this->load->library('upload', $config);
                        $this->upload->initialize($config);
                        if (!$this->upload->do_upload('fimage')) {
                            $array1 = ['status'=>false,'msg'=>"Featured Image : ".$this->upload->display_errors(),'csrf_token'=> $this->security->get_csrf_hash()];
                            echo json_encode($array1);exit;
                        } else {
                            $upload_data1 = $this->upload->data();
                            $insert['featuredimg'] = 'assets/products/' . $upload_data1['file_name'];
                        }
                    }
                    $insert['modalno'] = $mno;
                    $insert['tax'] = $tax;
                    $insert['youtubelink'] = $youtubeid;
                    $insert['created_at'] = date('Y-m-d h:i:s');
                    $insert['orderno'] = $orderno;
                    $products = $this->master_db->insertRecord('products',$insert);
                    if($products >0) {
                        if(is_array($site_type) && !empty($site_type[0])) {
                            foreach ($site_type as $key => $site) {
                                $s['pid'] = $products;
                                $s['type'] = $site;
                                $this->master_db->insertRecord('product_site_type',$s);
                            }
                        }
                        if(is_array($size)) {
                            foreach ($size as $key => $si) {
                                $db['pid'] = $products;
                                $db['sid'] = $si;
                                $db['mrp'] = $mrp[$key];
                                $db['selling_price'] = $sell[$key];
                                $db['stock'] = $stock[$key];
                                $sizeimg = $this->master_db->insertRecord('product_size',$db);
                            }
                        }
                        if(count($_FILES['pfile']['name'])) {
                            $count = count($_FILES['pfile']['name']);  
                            for($i=0;$i<$count;$i++){  
                              if(!empty($_FILES['pfile']['name'][$i])){  
                                $_FILES['photo']['name'] = $_FILES['pfile']['name'][$i];  
                                $_FILES['photo']['type'] = $_FILES['pfile']['type'][$i];  
                                $_FILES['photo']['tmp_name'] = $_FILES['pfile']['tmp_name'][$i];  
                                $_FILES['photo']['error'] = $_FILES['pfile']['error'][$i];  
                                $_FILES['photo']['size'] = $_FILES['pfile']['size'][$i];  
                                $config['upload_path'] = '../assets/products/';   
                                $config['allowed_types'] = 'jpg|jpeg|png|JPEG|JPG';  
                                $config['max_width'] = 800;
                                $config['max_height'] = 900;
                                $ext = pathinfo($_FILES["pfile"]['name'][$i], PATHINFO_EXTENSION);
                                $new_name = "SWARNA".rand(11111,99999).'.'.$ext; 
                                $config['file_name'] =  $new_name;
                                $this->load->library('upload',$config);  
                                $this->upload->initialize($config);  
                                if(!$this->upload->do_upload('photo')){  
                                    $array1 = ['status'=>false,'msg'=>"Product Image :".$this->upload->display_errors(),'csrf_token'=> $this->security->get_csrf_hash()];
                                    echo json_encode($array1);exit;
                                }else {
                                    $uploadData = $this->upload->data();  
                                    $filename = 'assets/products/'.$uploadData['file_name'];
                                    $dbs['pid'] = $products;
                                    $dbs['p_image'] = $filename;
                                    $this->master_db->insertRecord('product_images',$dbs);
                                }
                             }  
                           }  
                        }
                        $array = ['status'=>true,'msg'=>'Product saved successfully','csrf_token'=> $this->security->get_csrf_hash()];
                    }else {
                        $array = ['status'=>false,'msg'=>'Error in inserting','csrf_token'=> $this->security->get_csrf_hash()];
                    }
            }else {
                $array = ['status'=>false,'msg'=>'Required fields is missing','csrf_token'=> $this->security->get_csrf_hash()];
            }        }else {
          $array = array(
            'formerror'   => false,
            'cat_error' => form_error('category'),
            'sub_error' => form_error('subcategory'),
            'subsub_error' => form_error('subsubcategory'),
            'pro_error' => form_error('ptitle'),
            'pdesc_error' => form_error('pdesc'),
            'pcode_error' => form_error('pcode'),
            'pimage_error' => form_error('pfile[]'),
            'sell_error' => form_error('sell[0]'),
            'size_error' => form_error('size[0]'),
            'ylink_error' => form_error('ylink'),
            'mno_error' => form_error('mno'),
            'mtitle_error' => form_error('mtitle'),
            'mdesc_error' => form_error('mdesc'),
            'mkeywords_error' => form_error('mkeywords'),
            'discount_error' => form_error('discount'),
            'tax_error' => form_error('tax'),
            'pfeature_error' => form_error('fimage'),
            'csrf_token'=> $this->security->get_csrf_hash()
           );
        }
        echo json_encode($array);
    }
    public function setproductsStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('products', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == -1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'));
            $this->master_db->updateRecord('products', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'));
            $this->master_db->updateRecord('products', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
        else if ($status == 3) {
            $where_data = array('newarrivals' => 0);
            $this->master_db->updateRecord('products', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
        else if ($status == 4) {
            $where_data = array('newarrivals' => 1);
            $this->master_db->updateRecord('products', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
         else if ($status == 5) {
            $where_data = array('bestselling' => 0);
            $this->master_db->updateRecord('products', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
        else if ($status == 6) {
            $where_data = array('bestselling' => 1);
            $this->master_db->updateRecord('products', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
        else if ($status == 7) {
            $where_data = array('featured' => 0);
            $this->master_db->updateRecord('products', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
        else if ($status == 8) {
            $where_data = array('featured' => 1);
            $this->master_db->updateRecord('products', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    public function productViewdetails() {
        //echo "<pre>";print_r($_POST);exit;
        $id = icchaDcrypt($this->uri->segment(3));
        $query = "select p.id as pid,p.ptitle,p.pcode,p.overview,p.pspec,p.meta_title,p.meta_description,p.meta_keywords,p.tax,p.youtubelink,p.discount,p.length,p.weight,p.breadth,p.height,p.modalno,p.cqa,p.featuredimg,p.pbrochure,c.cname as catname,s.sname,ss.ssname,b.name as bname from products p left join category c on c.id =p.cat_id left join subcategory s on s.id = p.subcat_id left join subsubcategory ss on ss.id =p.sub_sub_id left join brand b on b.id= p.brand_id where p.id = ".$id.""; 
        $arr = $this->master_db->sqlExecute($query);
        $this->data['sizes'] = $this->master_db->sqlExecute('select ps.stock,ps.selling_price as price, ps.coimg,s.sname,co.name as coname from product_size ps left join sizes s on s.s_id = ps.sid left join colors co on co.co_id = ps.coid where ps.pid = '.$id.'');
        $this->data['images'] = $this->master_db->getRecords('product_images',['pid'=>$id],'*');
        $this->data['products'] = $arr;
        $this->load->view('productDetails',$this->data);
    }
    public function getSubcategoryview() {
      $id = trim($this->input->post('catid'));
      $getSubcat = $this->master_db->getRecords('subcategory',['cat_id'=>$id,'status'=>0],'id,sname');
      //echo $this->db->last_query();exit;
      $data ="";
      if(count($getSubcat)>0 ) {
        foreach ($getSubcat as $key => $value) {
            $data .="<option value=".$value->id.">".$value->sname."</option>";
        }
        $res = ['status'=>true,'msg'=>$data,'csrf_token'=> $this->security->get_csrf_hash()];
      }else {
        $res = ['status'=>false,'msg'=>'No data found','csrf_token'=> $this->security->get_csrf_hash()];
      }
      echo json_encode($res);
    }
    public function getSubsubcategoryview() {
      $id = trim($this->input->post('subid'));
      $getSubcat = $this->master_db->getRecords('subsubcategory',['sub_id'=>$id,'status'=>0],'id,ssname');
      //echo $this->db->last_query();exit;
      $data ="";
      if(count($getSubcat)>0 ) {
        foreach ($getSubcat as $key => $value) {
            $data .="<option value=".$value->id.">".$value->ssname."</option>";
        }
        $res = ['status'=>true,'msg'=>$data,'csrf_token'=> $this->security->get_csrf_hash()];
      }else {
        $res = ['status'=>false,'msg'=>'No data found','csrf_token'=> $this->security->get_csrf_hash()];
      }
      echo json_encode($res);
    }
    public function protitlevalidation() {
        //echo "<pre>";print_r($_POST);exit;
        $title = trim($this->input->post('title'));
        $getProducts = $this->master_db->getRecords('products',['ptitle'=>$title],'*');
        if(count($getProducts) >0) {
        	echo json_encode(['status'=>false,'msg'=>'Product title already exists try another','csrf_token'=> $this->security->get_csrf_hash()]);
        }else {
        	echo json_encode(['status'=>true,'csrf_token'=> $this->security->get_csrf_hash()]);
        }
    }
     public function procodevalidation() {
        //echo "<pre>";print_r($_POST);exit;
        $title = trim($this->input->post('title'));
        $getProducts = $this->master_db->getRecords('products',['pcode'=>$title],'*');
        if(count($getProducts) >0) {
        	echo json_encode(['status'=>false,'msg'=>'Product code already exists try another','csrf_token'=> $this->security->get_csrf_hash()]);
        }else {
        	echo json_encode(['status'=>true,'csrf_token'=> $this->security->get_csrf_hash()]);
        }
    }
    public function inventory() {
        $this->data['pcode'] = $this->master_db->getRecords('products',['status'=>0],'id,pcode','pcode asc');
        $this->data['getSize'] = $this->master_db->sqlExecute('select p.ptitle,p.pcode,ps.stock from products p left join product_size ps on p.id  = ps.pid where ps.stock <= 10');
        //echo $this->db->last_query();exit;
        $this->load->view('masters/inventory/inventory',$this->data);
    }
    public function inventoryUpdate() {
        //echo "<pre>";print_r($_POST);exit;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pcode','Pcode','required',['required'=>'Product code is required']);
         if($this->form_validation->run() ==TRUE) {
            $sizeid = $this->input->post('sizeid');
            $stock = $this->input->post('stock');
            if(is_array($sizeid) && !empty($sizeid)) {
                foreach ($sizeid as $key => $value) {
                    $db['stock'] = $stock[$key];
                    $getSize = $this->master_db->updateRecord('product_size',$db,['pro_size'=>$value]);
                }
               $resul = array(
                     'status'   => true,
                    'msg' => 'Updated successfully',
                    'csrf_token'=> $this->security->get_csrf_hash()
                ); 
            }else {
               $resul = array(
                     'status'   => false,
                    'msg' => 'No sizes found',
                    'csrf_token'=> $this->security->get_csrf_hash()
                );  
            }
         }else {
             $resul = array(
                     'formerror'   => false,
                    'pcode_error' => form_error('pcode'),
                    'csrf_token'=> $this->security->get_csrf_hash()
                );
         }
         echo json_encode($resul);
    }
    public function getSizedata() {
       // echo "<pre>";print_r($_POST);exit;
        $pid = icchaDcrypt($this->input->post('pid'));
        $getSize = $this->master_db->sqlExecute('select ps.pro_size as id,s.sname,ps.stock from product_size ps left join sizes s on s.s_id  = ps.sid where ps.pid = '.$pid.'');
        if(count($getSize)>0) {
            $html = "";
            foreach ($getSize as $key => $value) {
                $html .="<input type='hidden' name='sizeid[]' value='".$value->id."' /><p style='float:left;margin-top: 32px;'>Sizes : ".$value->sname."</p><div class='form-group' style='float:left;width:80%;margin-left: 10px;'><label style='width:100%'>Inventory</label><input type='text' name='stock[]' value='".$value->stock."' class='form-control' style='width:90%'/></div>"; 
            }
            $result = ['status'=>true,'msg'=>$html,'csrf_token'=> $this->security->get_csrf_hash()];
        }else {
            $result = ['status'=>false,'msg'=>'No sizes found','csrf_token'=> $this->security->get_csrf_hash()];
        }
        echo json_encode($result);
    }
     public function orders() {
        $this->data['orders'] = $this->master_db->getRecords('orders',['status !='=>6],'*');
        $this->data['pincodes'] = $this->master_db->getRecords('pincodes',['status'=>0],'id,pincode');
        $this->load->view('masters/orders/orders',$this->data);
    }
    public function getOrderslist() {
       // echo "<pre>";print_r($_POST);exit;
         $filters = "";
         if(!empty($_POST['form'][1]['value']) && $_POST['form'][1]['value'] !="") {
             $ostatus = $_POST['form'][1]['value'];
            $filters .=" and o.status =".$ostatus." ";
        }
        if(!empty($_POST['form'][2]['value']) && $_POST['form'][2]['value'] !="") {
             $pstatus = $_POST['form'][2]['value'];
            $filters .=" and p.status =".$pstatus." ";
        }
        if(!empty($_POST['form'][3]['value']) && $_POST['form'][3]['value'] !="") {
             $pmode = $_POST['form'][3]['value'];
            $filters .=" and o.pmode =".$pmode." ";
        }
        if(!empty($_POST['form'][4]['value']) && $_POST['form'][4]['value'] !="" && !empty($_POST['form'][5]['value']) && $_POST['form'][5]['value'] !="") {
             $fdate = $_POST['form'][4]['value']." 00:00:00";
            $tdate = $_POST['form'][5]['value']." 23:59:59";
            $filters .=" and o.order_date between '".$fdate."' and '".$tdate."' ";
        }
       
        $where = "where o.status !=6 ";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (o.orderid like '%$val%') ";
            $where.= " or (o.totalamount like '%$val%') ";
            $where.= " or (u.name like '%$val%') ";
            $where.= " or (o.lead_from like '%$val%') ";
        }
        $order_by_arr[] = "o.orderid";
        $order_by_arr[] = "";
        $order_by_arr[] = "o.oid";
        $order_by_def = " order by o.oid desc";
        $query = "select o.oid as id,o.orderid,o.order_date,o.totalamount,u.name as uname,o.pmode,o.status,o.invoice_status,o.invoice_pdf,p.pay_id,o.lead_from,p.pstatus,o.comments,u.otp from orders o left join users u on u.u_id = o.user_id left join payment_log p on p.oid = o.oid ".$where." ".$filters." group by o.orderid";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
       // echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $Comment  =[];
            $getComments = $this->master_db->getRecords('order_comments',['oid'=>$r->id],'*');
            if(count($getComments) >0) {
                foreach ($getComments as $key => $com) {
                    $Comment[] = $com->comments;
                }
            }else {
                $Comment[] = "";
            }
            $comm = implode("<br/>", $Comment);
            $oid = $r->id;$fname =[];$fran ="";
            $franchise = $this->master_db->sqlExecute('select f.franchise_name as name from assign_franchise a left join franchises f on f.franchise_id = a.franchise_id where a.oid='.$oid.' group by a.franchise_id ');
            if(count($franchise) >0) {
                foreach ($franchise as $key => $value) {
                    $fname[] = $value->name;
                }
                if(is_array($fname) && !empty($fname[0])) {
                    $fran .= implode(",", $fname);
                }
                
            }else {
                $fran .= "";
            }
            $action = "";
            $sub_array = array();
            $statustext = "";$paystatus = "";
          
            $action = '<a href="'.base_url()."orderdetails/".icchaEncrypt($r->id).'" class="btn btn-primary" title="View Order Details" style="margin-bottom:10px;">View Order Details</a>';
            if ($r->status == 1) {
                $statustext .= "In Progress";
            } else if ($r->status == 2){
                $statustext .= "Ready to Ship";
            }
            else if ($r->status == 3){
                $statustext .= "Delivered";
            }
            else if ($r->status == 4){
                $statustext .= "Order Cancelled";
            }
            else if ($r->status == 5){
                $statustext .= "Order Confirmed";
            }
            else if ($r->status == -1){
                $statustext .= "Failed";
            }
            if($r->pmode ==1) {
                $paystatus .="Online";
            }else if($r->pmode ==2) {
                $paystatus .="COD";
            }
            else if($r->pmode ==3) {
                $paystatus .="Wallet";
            }
            if($r->status ==2) {
                $action.= "<button title='Change to delivered' class='btn btn-warning' onClick='updateStatusdelivered(".$r->id.")' style='width:100%;margin-bottom:10px;'>Change to delivered</button>";
            }else if($r->status ==3) {
            }
            else if($r->status ==4) {
                
            }
            else {
                $action.= "<button title='Change to shipping' class='btn btn-danger' onClick='updateStatus(".$r->id.")' style='margin-bottom:10px;width:100%;'>Change to shipping</button>";
            }
            if($r->invoice_status ==0) {
                $action .= "<button title='Generate Invoice' class='btn btn-primary' onClick='generatePdf(".$r->id.")' style='margin-bottom:10px;width:100%;'>Generate PDF</button>";
            }
            else {
                if(!empty($r->invoice_pdf)) {
                    $action .= "<a href='".app_url().$r->invoice_pdf."' class='btn btn-danger' title='Download PDF' target='_blank' style='width:100%;margin-bottom:10px'>Download PDF</a>";
                    $action .= "<button title='Generate Invoice' class='btn btn-primary' onClick='regeneratePdf(".$r->id.")' style='margin-bottom:10px;width:100%;'>Regenerate PDF</button>";
                     $action .= "<button title='Send Email' class='btn btn-primary' onClick='sendMailtouser(".$r->id.")' style='margin-bottom:10px;width:100%;'>Send Mail</button>";
                    
                }else {
                        $action .= "<a href='javascript:void(0)' class='btn btn-danger' title='Download PDF' style='width:100%;'>Download PDF</a>&nbsp;";
                }
            }
            if($r->status ==4) {

            }else {
                $action .= "<button title='Order Cancel' class='btn btn-primary' onClick='cancelOrder(".$r->id.")' style='margin-bottom:10px;width:100%;'>Cancel Order</button>";
            }
            
        if($this->data['id'] ==1){
                $action .= "<button title='Comments' class='btn btn-primary' onClick='commentView(".$r->id.")' style='margin-bottom:10px;width:100%;'>Comments</button>";
            }else {
                
            }
            if($r->status ==3) {

            }else if($r->status ==1) {

            }
            else if($r->status ==-1) {

            }
            else if($r->status ==4) {

            }
            else {
                $action .= "<button title='Assign Franchises' class='btn btn-primary' onClick='assignFranchise(".$r->id.")' style='margin-bottom:10px;width:100%;'>Assign Franchise</button>";
            }
            
            
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $r->orderid;
            $sub_array[] = $fran;
            $sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> " . date('d M y', strtotime($r->order_date)) . "<br/>" . "<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> " . date('h:i:s A', strtotime($r->order_date));
            $sub_array[] = $statustext;
            $sub_array[] =$r->uname;
            $sub_array[] =$paystatus;
            $sub_array[] =$r->otp;
            $sub_array[] =$comm;
            $sub_array[] =$r->pay_id;
            $sub_array[] =$r->pstatus;
            $sub_array[] ="<i class='fas fa-indian-rupee' style='color:#620404;margin-right:3px'></i> ".$r->totalamount;
            
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function orderdetails() {
        $id = icchaDcrypt($this->uri->segment(2));
        $this->data['order_details'] = $this->master_db->sqlExecute('select o.orderid,o.totalamount,o.discount,o.delivery_charges,o.taxamount,o.subtotal,o.pmode,o.order_date,o.shipping_date,o.delivered_date,o.status,u.name as uname from orders o left join users u on u.u_id = o.user_id where o.oid='.$id.'');
        $this->data['baddress'] = $this->master_db->sqlExecute('select ob.bfname,ob.bemail,ob.bphone,ob.bpincode,ob.bstate,ob.bcity,ob.barea,ob.baddress,a.areaname,s.name as sname,c.cname from  order_bills ob left join states s on s.id = ob.bstate left join cities c on c.id = ob.bcity left join area a on a.id = ob.barea where ob.oid='.$id.'');
        $this->data['saddress'] = $this->master_db->sqlExecute('select ob.sfname,ob.sphone,ob.spincode,ob.saddress,a.areaname,s.name as sname,c.cname from  order_bills ob left join states s on s.id = ob.sstate left join cities c on c.id = ob.scity left join area a on a.id = ob.sarea where ob.oid='.$id.'');
        $this->data['order_products'] = $this->master_db->getRecords('order_products',['oid'=>$id],'*');
        $this->data['logs'] = $this->master_db->getRecords('payment_log',['oid'=>$id],'*');
        //echo $this->db->last_query();
        $this->load->view('masters/orders/ordersdetails',$this->data);
    }
    public function updateShipping() {
        //echo "<pre>";print_r($_POST);exit;
        $this->form_validation->set_rules('sdate','Shipping date ','required',['required'=>'Shipping date is required']);
        if($this->form_validation->run() ==TRUE) {
            $orderid = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('orderid', true))));
            $sdate = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('sdate', true))));
            $this->master_db->updateRecord('orders',['status'=>2,'shipping_date'=>$sdate],['oid'=>$orderid]);
            $array = ['status'=>true,'msg'=>'Shipping updated successfully','csrf_token'=> $this->security->get_csrf_hash()];
        }else {
             $array = array(
            'formerror'   => false,
            'sdate_error' => form_error('sdate'),
            'csrf_token'=> $this->security->get_csrf_hash()
           );
        }
        echo json_encode($array);
    }
    public function updateDelivered() {
        $this->load->library('Mail');
       // echo "<pre>";print_r($_POST);exit;
        $this->form_validation->set_rules('ddate','Delivery date ','required',['required'=>'Delivery date is required']);
        if($this->form_validation->run() ==TRUE) {
            $orderid = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('orderid', true))));
            $this->data['orders'] = $this->master_db->getRecords('orders',['oid'=>$orderid],'orderid');

            $bills = $this->master_db->getRecords('order_bills',['oid'=>$orderid],'bemail');
           //   echo $this->db->last_query();exit;
            $html = $this->load->view('dorder_cancel',$this->data,true);
            $this->mail->sendMail($bills[0]->bemail,$html,'Your Delivery Status');
            $sdate = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('ddate', true))));
            $this->master_db->updateRecord('orders',['status'=>3,'delivered_date'=>$sdate],['oid'=>$orderid]);
            $array = ['status'=>true,'msg'=>'Delivery updated successfully','csrf_token'=> $this->security->get_csrf_hash()];
        }else {
             $array = array(
            'formerror'   => false,
            'ddate_error' => form_error('ddate'),
            'csrf_token'=> $this->security->get_csrf_hash()
           );
        }
        echo json_encode($array);
    }
    /*********** Reviews **********/
    public function reviews() {
        $this->load->view('reviews',$this->data);
    }
    public function getreviewslist() {
        $where = "where r.status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (r.review_descp like '%$val%') ";
            $where.= " or (u.name like '%$val%') ";
            $where.= " or (u.rating like '%$val%') ";
            $where.= " or (p.ptitle like '%$val%') ";
        }
        $order_by_arr[] = "u.name";
        $order_by_arr[] = "";
        $order_by_arr[] = "r.id";
        $order_by_def = " order by r.id desc";
        $query = "select r.id,r.review_descp,r.rating,u.name as uname,p.ptitle,r.status,DATE_FORMAT(r.created_at,'%d-%m-%Y') as date from reviews r left join users u on u.u_id = r.user_id left join products p on p.id = r.pid " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
           
            
            if ($r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", 1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Active' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
          
            $sub_array[] = $i++;
            $sub_array[] = $action;
            // $sub_array[] = $image;
            $sub_array[] = ucfirst($r->uname);
            $sub_array[] = $r->ptitle;
            $sub_array[] = $r->review_descp;
            $sub_array[] = $r->rating;
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
     public function setreviewsStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('reviews', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('reviews', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('reviews', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    /*********** Today List **********/
       public function getTodaylist() {
        $where = "where o.status !=6 and o.datewise= '".date('Y-m-d')."'";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (o.orderid like '%$val%') ";
            $where.= " or (o.totalamount like '%$val%') ";
            $where.= " or (u.name like '%$val%') ";
            $where.= " or (o.lead_from like '%$val%') ";
        }
        $order_by_arr[] = "o.orderid";
        $order_by_arr[] = "";
        $order_by_arr[] = "o.oid";
        $order_by_def = " order by o.oid desc";
        $query = "select o.oid as id,o.orderid,o.order_date,o.totalamount,u.name as uname,o.pmode,o.status,o.invoice_status,o.invoice_pdf,p.pay_id,o.lead_from,p.pstatus,o.comments from orders o left join users u on u.u_id = o.user_id left join payment_log p on p.oid = o.oid " . $where . " group by o.orderid";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
       // echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
             $Comment  =[];
            $getComments = $this->master_db->getRecords('order_comments',['oid'=>$r->id],'*');
            if(count($getComments) >0) {
                foreach ($getComments as $key => $com) {
                    $Comment[] = $com->comments;
                }
            }else {
                $Comment[] = "";
            }
            $comm = implode("<br/>", $Comment);
            $action = "";
            $sub_array = array();
            $statustext = "";$paystatus = "";
          
            $action = '<a href="'.base_url()."orderdetails/".icchaEncrypt($r->id).'" class="btn btn-primary" title="View Order Details" style="margin-bottom:10px;">View Order Details</a>';
                if ($r->status == 1) {
                $statustext .= "In Progress";
            } else if ($r->status == 2){
                $statustext .= "Ready to Ship";
            }
            else if ($r->status == 3){
                $statustext .= "Delivered";
            }
            else if ($r->status == 4){
                $statustext .= "Order Cancelled";
            }
            else if ($r->status == 5){
                $statustext .= "Order Confirmed";
            }
            else if ($r->status == -1){
                $statustext .= "Failed";
            }
            if($r->pmode ==1) {
                $paystatus .="Online";
            }else if($r->pmode ==2) {
                $paystatus .="COD";
            }
            else if($r->pmode ==3) {
                $paystatus .="Wallet";
            }
            if($r->status ==2) {
                $action.= "<button title='Change to delivered' class='btn btn-warning' onClick='updateStatusdelivered(".$r->id.")' style='margin-bottom:10px;width:100%;'>Change to delivered</button>";
            }else if($r->status ==3) {
            }else if($r->status ==4){
            }else {
                 $action.= "<button title='Change to shipping' class='btn btn-danger' onClick='updateStatus(".$r->id.")' style='margin-bottom:10px;width:100%;'>Change to shipping</button>";
            }
            if($r->invoice_status ==0) {
                $action .= "<button title='Generate Invoice' class='btn btn-primary' onClick='generatePdf(".$r->id.")' style='margin-bottom:10px;width:100%;'>Generate PDF</button>";
            }
            else {
                if(!empty($r->invoice_pdf)) {
                    $action .= "<a href='".app_url().$r->invoice_pdf."' class='btn btn-danger' title='Download PDF' target='_blank' style='width:100%;margin-bottom:10px;'>Download PDF</a>";
                    $action .= "<button title='Generate Invoice' class='btn btn-primary' onClick='regeneratePdf(".$r->id.")' style='margin-bottom:10px;width:100%;'>Regenerate PDF</button>";
                     $action .= "<button title='Send Email' class='btn btn-primary' onClick='sendMailtouser(".$r->id.")' style='margin-bottom:10px;width:100%;'>Send Mail</button>";
                }else {
                        $action .= "<a href='javascript:void(0)' class='btn btn-danger' title='Download PDF' style='width:100%;'>Download PDF</a>&nbsp;";
                }
            }
            if($r->status ==4) {

            }else {
                $action .= "<button title='Order Cancel' class='btn btn-primary' onClick='cancelOrder(".$r->id.")' style='margin-bottom:10px;width:100%;'>Cancel Order</button>";
            }
            if($this->data['id'] ==1){
                $action .= "<button title='Comments' class='btn btn-primary' onClick='commentView(".$r->id.")' style='margin-bottom:10px;width:100%;'>Comments</button>";
            }else {
                
            }
            
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $r->orderid;
            $sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> " . date('d M y', strtotime($r->order_date)) . "<br/>" . "<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> " . date('h:i:s A', strtotime($r->order_date));
            $sub_array[] = $statustext;
            $sub_array[] =$r->uname;
            $sub_array[] =$paystatus;
            $sub_array[] =$comm;
            $sub_array[] =$r->pay_id;
             $sub_array[] =$r->pstatus;
            $sub_array[] ="<i class='fas fa-indian-rupee' style='color:#620404;margin-right:3px'></i> ".number_format($r->totalamount);
            
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function generatePDF() {

    }
    public function sendMailtouser() {
        
    }
    /**** Subscribe *******/
    public function subscribe() {
        $this->load->view('subscribe',$this->data);
    }
     public function getSubscribe() {
        $where = "where id !=-1";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (email like '%$val%') ";
        }
        $order_by_arr[] = "email";
        $order_by_arr[] = "";
        $order_by_arr[] = "id";
        $order_by_def = " order by id desc";
        $query = "select * from subscribe " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $sub_array = array();
           
            // $action .= "<button title='View Detail' class='btns' onClick='popUp()'><i class='fas fa-eye' ></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $r->email;
            $sub_array[] = date('d-m-Y h:i:s A',strtotime($r->created_at));
            //$sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> ".date('d M y',strtotime($r->created_at))."<br/>"."<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> ".date('h:i:s A',strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
     /**** Contest *******/
    public function contest() {
        $this->load->view('contest',$this->data);
    }
     public function getContest() {
        $where = "where id !=-1";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (name like '%$val%') ";
            $where.= " or (mobileno like '%$val%') ";
        }
        $order_by_arr[] = "name";
        $order_by_arr[] = "";
        $order_by_arr[] = "id";
        $order_by_def = " order by id desc";
        $query = "select * from contest " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $sub_array = array();
           
            // $action .= "<button title='View Detail' class='btns' onClick='popUp()'><i class='fas fa-eye' ></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $r->name;
            $sub_array[] = $r->mobileno;
            $sub_array[] = date('d-m-Y h:i:s A',strtotime($r->created_at));
            //$sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> ".date('d M y',strtotime($r->created_at))."<br/>"."<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> ".date('h:i:s A',strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    /***** Guest Orders *******/
     public function guestorders() {
        $this->data['orders'] = $this->master_db->getRecords('guest_orders',['status !='=>6],'*');
        $this->load->view('masters/guests/orders',$this->data);
    }
    public function getguestOrderslist() {
        $where = "where o.status !=6";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (o.orderid like '%$val%') ";
            $where.= " or (o.totalamount like '%$val%') ";
            $where.= " or (u.name like '%$val%') ";
        }
        $order_by_arr[] = "o.orderid";
        $order_by_arr[] = "";
        $order_by_arr[] = "o.oid";
        $order_by_def = " order by o.oid desc";
        $query = "select o.oid as id,o.orderid,o.order_date,o.totalamount,u.bfname as uname,o.pmode,o.status,o.invoice_status,o.invoice_pdf,p.pay_id,p.pstatus,o.comments from guest_orders o left join guest_order_bills u on u.oid = o.oid left join guest_payment_log p on p.oid =o.oid " . $where . " group by o.orderid";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
       // echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $statustext = "";$paystatus = "";
          
            $action = '<a href="'.base_url()."guestorderdetails/".icchaEncrypt($r->id).'" class="btn btn-primary" title="View Order Details" style="margin-bottom:10px;">View Order Details</a>';
            if ($r->status == 1) {
                $statustext .= "In Progress";
            } else if ($r->status == 2){
                $statustext .= "Ready to Ship";
            }
            else if ($r->status == 3){
                $statustext .= "Delivered";
            }
            else if ($r->status == 4){
                $statustext .= "Cancelled";
            }
            else if ($r->status == 5){
                $statustext .= "In Transit";
            }
            else if ($r->status == -1){
                $statustext .= "Failed";
            }
            if($r->pmode ==1) {
                $paystatus .="Online";
            }else if($r->pmode ==2) {
                $paystatus .="COD";
            }
            if($r->status ==2) {
                $action.= "<button title='Change to delivered' class='btn btn-warning' onClick='updateStatusdelivered(".$r->id.")' style='width:100%;margin-bottom:10px;'>Change to delivered</button>";
            }else if($r->status ==3) {
            }else {
                $action.= "<button title='Change to shipping' class='btn btn-danger' onClick='updateStatus(".$r->id.")' style='margin-bottom:10px;width:100%;'>Change to shipping</button>";
            }
            if($r->invoice_status ==0) {
                $action .= "<button title='Generate Invoice' class='btn btn-primary' onClick='generatePdf(".$r->id.")' style='margin-bottom:10px;width:100%;'>Generate PDF</button>";

            }
            else {
                if(!empty($r->invoice_pdf)) {
                    $action .= "<a href='".app_url().$r->invoice_pdf."' class='btn btn-danger' title='Download PDF' target='_blank' style='width:100%;margin-bottom:10px'>Download PDF</a>";
                    $action .= "<button title='Generate Invoice' class='btn btn-primary' onClick='regeneratePdf(".$r->id.")' style='margin-bottom:10px;width:100%;'>Regenerate PDF</button>";
                    $action .= "<button title='Download PDF' class='btn btn-primary' onClick='sendMailtouser(".$r->id.")' style='margin-bottom:10px;width:100%;'>Send Mail</button>";
                }else {
                        $action .= "<a href='javascript:void(0)' class='btn btn-danger' title='Download PDF' style='width:100%;'>Download PDF</a>&nbsp;";
                }
            }
            $action .= "<button title='Comments' class='btn btn-primary' onClick='commentView(".$r->id.")' style='margin-bottom:10px;width:100%;'>Comments</button>";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $r->orderid;
            $sub_array[] = "<i class='fas fa-calendar' style='color:#620404;margin-right:3px'></i> " . date('d M y', strtotime($r->order_date)) . "<br/>" . "<i class='fas fa-clock' style='color:#620404;margin-right:3px'></i> " . date('h:i:s A', strtotime($r->order_date));
            $sub_array[] = $statustext;
            $sub_array[] =$r->uname;
            $sub_array[] =$paystatus;
            $sub_array[] =$r->comments;
            $sub_array[] =$r->pay_id;
            $sub_array[] =$r->pstatus;
            
            $sub_array[] ="<i class='fas fa-indian-rupee' style='color:#620404;margin-right:3px'></i> ".number_format($r->totalamount);
            
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function guestorderdetails() {
        $id = icchaDcrypt($this->uri->segment(2));
        $this->data['order_details'] = $this->master_db->sqlExecute('select o.orderid,o.totalamount,o.discount,o.delivery_charges,o.taxamount,o.subtotal,o.pmode,o.order_date,o.shipping_date,o.delivered_date,o.status,u.bfname as uname from guest_orders o left join guest_order_bills u on u.oid = o.oid where o.oid='.$id.'');
        $this->data['baddress'] = $this->master_db->sqlExecute('select ob.bfname,ob.bemail,ob.bphone,ob.bpincode,ob.bstate,ob.bcity,ob.barea,ob.baddress,a.areaname,s.name as sname,c.cname from  guest_order_bills ob left join states s on s.id = ob.bstate left join cities c on c.id = ob.bcity left join area a on a.id = ob.barea where ob.oid='.$id.'');
        $this->data['saddress'] = $this->master_db->sqlExecute('select ob.sfname,ob.sphone,ob.spincode,ob.saddress,a.areaname,s.name as sname,c.cname from  guest_order_bills ob left join states s on s.id = ob.sstate left join cities c on c.id = ob.scity left join area a on a.id = ob.sarea where ob.oid='.$id.'');
        $this->data['order_products'] = $this->master_db->getRecords('guest_order_products',['oid'=>$id],'*');
        $this->data['payment'] = $this->master_db->getRecords('guest_payment_log',['oid'=>$id],'*');
        $this->data['logs'] = $this->master_db->getRecords('guest_payment_log',['oid'=>$id],'*');
        //echo $this->db->last_query();
        $this->load->view('masters/guests/ordersdetails',$this->data);
    }
    public function updateguestShipping() {
        //echo "<pre>";print_r($_POST);
        $this->form_validation->set_rules('sdate','Shipping date ','required',['required'=>'Shipping date is required']);
        if($this->form_validation->run() ==TRUE) {
            $orderid = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('orderid', true))));
            $sdate = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('sdate', true))));
            $this->master_db->updateRecord('guest_orders',['status'=>2,'shipping_date'=>$sdate],['oid'=>$orderid]);
            $array = ['status'=>true,'msg'=>'Shipping updated successfully','csrf_token'=> $this->security->get_csrf_hash()];
        }else {
             $array = array(
            'formerror'   => false,
            'sdate_error' => form_error('sdate'),
            'csrf_token'=> $this->security->get_csrf_hash()
           );
        }
        echo json_encode($array);
    }
    public function updateguestDelivered() {
        //echo "<pre>";print_r($_POST);
        $this->form_validation->set_rules('ddate','Delivery date ','required',['required'=>'Delivery date is required']);
        if($this->form_validation->run() ==TRUE) {
            $orderid = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('orderid', true))));
            $sdate = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('ddate', true))));
            $this->master_db->updateRecord('guest_orders',['status'=>3,'delivered_date'=>$sdate],['oid'=>$orderid]);
            $array = ['status'=>true,'msg'=>'Delivery updated successfully','csrf_token'=> $this->security->get_csrf_hash()];
        }else {
             $array = array(
            'formerror'   => false,
            'ddate_error' => form_error('ddate'),
            'csrf_token'=> $this->security->get_csrf_hash()
           );
        }
        echo json_encode($array);
    }
    public function pinnotification() {
        $this->load->view('pinnotification',$this->data);
    }
     public function getpincodenotifylist() {
        $where = "where id !=-1";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (phone_number like '%$val%') ";
            $where.= " or (emailid like '%$val%') ";
            
        }
        $order_by_arr[] = "phone_number";
        $order_by_arr[] = "";
        $order_by_arr[] = "id";
        $order_by_def = " order by id desc";
        $query = "select * from notification  " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
           
          
            $sub_array[] = $i++;
            // $sub_array[] = $image;
            $sub_array[] = $r->phone_number;
            $sub_array[] = $r->emailid;
            $sub_array[] = date('d-m-Y h:i:s A',strtotime($r->created_at));
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
       public function updateComment() {
        $this->form_validation->set_rules('comment','Comment','required',['required'=>'Comment is required']);
        if($this->form_validation->run() ==TRUE) {
            $orderid = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('orderid', true))));
            $comment = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('comment', true))));
            $db['oid'] = $orderid;
            $db['comments'] = $comment;
            $this->master_db->insertRecord('order_comments',$db);
            $array = ['status'=>true,'msg'=>'Comment updated successfully','csrf_token'=> $this->security->get_csrf_hash()];
        }else {
             $array = array(
            'formerror'   => false,
            'comments_error' => form_error('comment'),
            'csrf_token'=> $this->security->get_csrf_hash()
           );
        }
        echo json_encode($array);
    }
     public function updateCommentguest() {
        //echo "<pre>";print_r($_POST);
        $this->form_validation->set_rules('comment','Comment','required',['required'=>'Comment is required']);
        if($this->form_validation->run() ==TRUE) {
            $orderid = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('orderid', true))));
            $comment = trim(preg_replace('!\s+!', ' ',html_escape($this->input->post('comment', true))));
            $this->master_db->updateRecord('guest_orders',['comments'=>$comment],['oid'=>$orderid]);
            $array = ['status'=>true,'msg'=>'Comment updated successfully','csrf_token'=> $this->security->get_csrf_hash()];
        }else {
             $array = array(
            'formerror'   => false,
            'comments_error' => form_error('comment'),
            'csrf_token'=> $this->security->get_csrf_hash()
           );
        }
        echo json_encode($array);
    }
    public function cancelOrders() {
         $this->load->library('Mail');
        //echo "<pre>";print_r($_POST);exit;
        $oid = $this->input->post('oid');
         $update = $this->master_db->updateRecord('orders',['status'=>4],['oid'=>$oid]);
         if($update >0) {
            $getPay = $this->master_db->sqlExecute('select o.subtotal,o.user_id from orders o left join payment_log p on p.oid = o.oid where o.oid='.$oid.' and o.status =4 and p.status =1');
            if(count($getPay) >0) {
                $or['order_id'] = $oid;
                $or['user_id'] = $getPay[0]->user_id;
                $or['amount'] = $getPay[0]->subtotal;
                $this->master_db->insertRecord('referrel_points',$or);
            }
         }
        $this->data['orders'] = $this->master_db->getRecords('orders',['oid'=>$oid],'orderid');
        $bills = $this->master_db->getRecords('order_bills',['oid'=>$oid],'*');
            $html = $this->load->view('porder_cancel',$this->data,true);
            $this->mail->sendMail($bills[0]->bemail,$html,'Order Cancellation');

        echo json_encode(['status'=>true,'msg'=>'Order has been cancelled successfully']);
    }
    /********* Franchises ******/
    public function franchises() {
        $this->load->view('masters/franchises/franchises', $this->data);
    }
     public function getfranchises() {
        $where = "where status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (franchise_name like '%$val%') ";
            $where.= " or (name_of_person like '%$val%') ";
            $where.= " or (contact_no like '%$val%') ";
            $where.= " or (whatsapp_no like '%$val%') ";
            $where.= " or (address like '%$val%') ";
        }
        $order_by_arr[] = "name";
        $order_by_arr[] = "";
        $order_by_arr[] = "franchise_id";
        $order_by_def = " order by franchise_id desc";
        $query = "select * from franchises " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $action.= '<a href="'.base_url()."editfranchise/".icchaEncrypt($r->franchise_id).'" class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            $status = '';
            $type = "";
            if ((int)$r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->franchise_id . ", -1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Activate' class='btn btn-warning' onclick='updateStatus(" . $r->franchise_id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->franchise_id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            $action.= "<button title='View Franchise Details' class='btn btn-success' onclick='viewFranchisedetails(" . $r->franchise_id . ")' ><i class='fas fa-eye'></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $r->franchise_name;
            $sub_array[] = $r->name_of_person;
            $sub_array[] = $r->contact_no;
            $sub_array[] = $r->whatsapp_no;
            $sub_array[] = $r->address;
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function franchisesadd() {
        $this->data['pincodes'] = $this->master_db->getRecords('pincodes',['status'=>0],'id,pincode');
        $this->load->view('masters/franchises/franchisesadd', $this->data);
    }
    public function saveFranchise() {
       // echo "<pre>";print_r($_POST);exit;
        $fid = $this->input->post('fid');
        $pinid = $this->input->post('pinid');
        $this->form_validation->set_rules('fname','Franchise','required',['required'=>'Franchise name is required']);
        $this->form_validation->set_rules('address','Address','required',['required'=>'Address is required']);
        if($fid ==0) {
            $this->form_validation->set_rules('pincodes[]','Pincodes','required',['required'=>'Pincode is required']);
        }
        if($this->form_validation->run() ==TRUE) {
            $fname = trim($this->input->post('fname'));
            $pname = trim($this->input->post('pname'));
            $cno = trim($this->input->post('cno'));
            $wno = trim($this->input->post('wno'));
            $address = trim($this->input->post('address'));
            $area = trim($this->input->post('area'));
            $subarea = trim($this->input->post('subarea'));
            $street = trim($this->input->post('street'));
            $gatedcommunity = trim($this->input->post('gatedcommunity'));
            if($fid ==0) {
                 $pincodes = $this->input->post('pincodes');
                 $getfranchise = $this->master_db->getRecords('franchises',['contact_no'=>$cno,'status !='=>2],'*');
                if(count($getfranchise) >0) {
                    $array = ['status'=>false,'msg'=>'Contact number already exists try another'];
                }else {
                    $franchise['franchise_name'] = $fname;
                    $franchise['name_of_person'] = $pname;
                    $franchise['contact_no'] = $cno;
                    $franchise['whatsapp_no'] = $wno;
                    $franchise['address'] = $address;
                    $franchise['area'] = $area;
                    $franchise['subarea'] = $subarea;
                    $franchise['street'] = $street;
                    $franchise['gated_community'] = $gatedcommunity;
                    $franchise['created_at'] =date('Y-m-d H:i:s');
                    $in = $this->master_db->insertRecord('franchises',$franchise);
                    if($in >0) {
                        if(is_array($pincodes) && !empty($pincodes[0])) {
                            foreach ($pincodes as $key => $value) {
                                $franchisepin['franchise_id'] = $in;
                                $franchisepin['pincode_id'] = $value;
                                $this->master_db->insertRecord('franchises_pincodes',$franchisepin);
                            }
                        }
                        $array = ['status'=>true,'msg'=>'Franchise saved successfully'];
                    }else {
                        $array = ['status'=>false,'msg'=>'Error in saving'];
                    }
                }
            }else {
                    $pincode = $this->input->post('pincodes');
                    $getFranchisepin = $this->master_db->getRecords('franchises_pincodes',['franchise_id'=>$fid],'id');
                    $getPins = [];
                    if(count($getFranchisepin) >0) {
                        foreach ($getFranchisepin as $key => $value) {
                            $getPins[] = $value->id;
                        }
                    }
                    $update['franchise_name'] = $fname;
                    $update['name_of_person'] = $pname;
                    $update['contact_no'] = $cno;
                    $update['whatsapp_no'] = $wno;
                    $update['address'] = $address;
                    $update['area'] = $area;
                    $update['subarea'] = $subarea;
                    $update['street'] = $street;
                    $update['gated_community'] = $gatedcommunity;
                    $this->master_db->updateRecord('franchises',$update,['franchise_id'=>$fid]);
                    if(is_array($pinid) && !empty($pinid[0])) {
                        foreach ($pinid as $key => $pins) {
                            if(in_array($pins, $getPins)) {
                                $updates['pincode_id'] = $pincode[$key];
                                $this->master_db->updateRecord('franchises_pincodes',$updates,['id'=>$pins]);
                            }else {
                                $inserts['franchise_id'] = $fid;
                                $inserts['pincode_id'] = $pincode[$key];
                                $this->master_db->insertRecord('franchises_pincodes',$inserts);
                            }
                        }
                    }
                    $array = ['status'=>true,'msg'=>'Franchise updated successfully'];
            }
          }
          else {
                $array = array(
                    'formerror'   => false,
                    'fname_error' => form_error('fname'),
                    'address_error' => form_error('address'),
                    'pincodes_error' => form_error('pincodes[]'),
                    'csrf_token'=> $this->security->get_csrf_hash()
                   );
        }
        echo json_encode($array);
    }
    public function editfranchise() {
        $id = icchaDcrypt($this->uri->segment(2));
        $this->data['edit'] = $this->master_db->getRecords('franchises',['franchise_id'=>$id],'*');
        $this->data['pincodes'] = $this->master_db->getRecords('pincodes',['status'=>0],'*');
        $this->data['franchisepin'] =  $getfranchise = $this->master_db->sqlExecute('select fp.id,p.pincode,fp.pincode_id from franchises_pincodes fp left join pincodes p on p.id = fp.pincode_id where fp.franchise_id='.$id.'');
        $this->load->view('masters/franchises/franchisesedit',$this->data);
    }
     public function setfranchiseStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'));
            $this->master_db->updateRecord('franchises', $where_data,['franchise_id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == - 1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'));
            $this->master_db->updateRecord('franchises', $where_data, array('franchise_id ' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'));
            $this->master_db->updateRecord('franchises', $where_data, array('franchise_id ' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
     public function assignFranchise() {
        //echo "<pre>";print_r($_POST);exit;
        $this->load->library('SMS');
        $this->load->library('notify');
        $oid = $this->input->post('oid');
        $type = 4;
        $pincode = $this->input->post('pincode');
        $franchise = $this->input->post('franchise');
        $this->form_validation->set_rules('pincode[]','Pincodes','required',['required'=>'Pincode is required']);
        $this->form_validation->set_rules('franchise[]','Franchise','required',['required'=>'Franchise name is required']);
           if($this->form_validation->run() ==TRUE) {
            $invpin = implode(",", $pincode);
                $getInvPin = $this->master_db->sqlExecute('select * from inv_pincodes where pincode in ('.$invpin.')');
                if(count($getInvPin) >0) {
                    if(is_array($pincode) && !empty($pincode[0])) {
                         $updates['status'] = 2;
                    $this->master_db->updateRecord('orders',$updates,['oid'=>$oid]);
                    $pincodes = implode(",", $pincode);
                    $getQ = $this->master_db->sqlExecute('select * from assign_franchise where oid='.$oid.' and pincode_id in ('.$pincodes.')');
                    $getMemberorder = $this->master_db->sqlExecute('select m.name,m.id from member_pincodes mp left join members m on m.id=mp.member_id left join where m.status=1 and mp.status=1 and mp.pincode_id in ('.$pincodes.')');
                    $getOrders = $this->master_db->getRecords('orders',['oid'=>$oid,'user_id !='=>0],'*');
                    if(is_array($franchise) && !empty($franchise)) {
                        $fraid = implode(",", $franchise);
                        $Franchisede = $this->master_db->sqlExecute('select fcm_id,franchise_id from franchises where franchise_id in ('.$fraid.')');
                        if(count($Franchisede) >0) {
                            foreach ($Franchisede as $key => $frad) {
                                if(!empty($frad->fcm_id) && $frad->fcm_id !="") {
                                    $msg = "New order ".$getOrders[0]->orderid." has been assigned to you";
                                    $noti['franchise_id'] = $frad->franchise_id;
                                    $noti['order_id'] = $getOrders[0]->oid;
                                    $noti['message'] = $msg;
                                    $noti['created_at'] = date('Y-m-d H:i:s');
                                   $this->master_db->insertRecord('franchise_notification_logs',$noti);
                                    $message_status = $this->notify->sendNotification($frad->fcm_id, $msg,"Delivery app notification");
                                }
                            }
                        }
                    }
                    if(count($getQ) >0) {
                        $array = ['status'=>false,'msg'=>'Franchise already assigned try another','csrf_token'=> $this->security->get_csrf_hash()];
                    }else {
                        if(count($getOrders) >0) {
                            $getUsers = $this->master_db->getRecords('users',['u_id'=>$getOrders[0]->user_id],'u_id,name,email,phone');
                            //echo $this->db->last_query();exit;

                            if(count($getUsers) >0) {
                                $name = "";
                                if(!empty($getUsers[0]->name) && $getUsers[0]->name !=null) {
                                    $name .=$getUsers[0]->name;
                                }else {
                                    $name .="User";
                                }
                                $otp = rand(2234,9838);
                                  $upuser['otp'] = $otp;
                                  $this->master_db->updateRecord('users',$upuser,['u_id'=>$getUsers[0]->u_id]);
                                 $message = "Dear User, Thank you for shopping Swarnagowri products. Please share the OTP {#var#} at the time of delivery. Regards Swarnagowri";
                            //echo $message;exit;
                                $message = str_replace('{#var#}',$otp,$message);
                            // echo $message;exit;
                                $this->sms->sendSmsToUser($message,$getUsers[0]->phone);
                                 $ootp['order_id'] = $getOrders[0]->oid;
                                 $ootp['user_id'] = $getUsers[0]->u_id;
                                 $ootp['otp'] = $otp;
                                 $this->master_db->insertRecord('order_otp',$ootp);
                                 if(count($getMemberorder) >0) {
                                    foreach ($getMemberorder as $key => $value) {
                                         $morders['oid'] = $getOrders[0]->oid;
                                         $morders['member_id'] = $value->id;
                                         $morders['otp'] = $otp;
                                         $morders['status'] = 1;
                                         $morders['created_at'] = date('Y-m-d H:i:s');
                                         $morders['created_by'] = 1;
                                         $this->master_db->insertRecord('member_orders',$morders);
                                    }
                                  }
                            }
                        }else {
                            $getGorders = $this->master_db->getRecords('order_bills',['oid'=>$pincodeid],'bfname,bphone');
                            //echo $this->db->last_query();exit;
                            $name = "";
                            if(!empty($getGorders[0]->bfname) && $getGorders[0]->bfname !=null) {
                                $name .=$getGorders[0]->bfname;
                            }else {
                                $name .="User";
                            }
                                $otp = rand(1234,9988);
                                 $message = "Dear User, Thank you for shopping Swarnagowri products. Please share the OTP {#var#} at the time of delivery. Regards Swarnagowri";
                            //echo $message;exit;
                            $message = str_replace('{#var#}',$otp,$message);
                            // echo $message;exit;
                            $this->sms->sendSmsToUser($message,$getGorders[0]->bphone);
                             if(count($getMemberorder) >0) {
                                    foreach ($getMemberorder as $key => $value) {
                                         $morders['oid'] = $oid;
                                         $morders['member_id'] = $value->id;
                                         $morders['otp'] = $otp;
                                         $morders['status'] = 1;
                                         $morders['created_at'] = date('Y-m-d H:i:s');
                                         $morders['created_by'] = 1;
                                         $this->master_db->insertRecord('member_orders',$morders);
                                    }
                                  }
                        }
                        foreach ($pincode as $key => $value) {
                            $assign['oid'] = $oid;
                            if(!empty($franchise[$key]) && $franchise[$key] !="") {
                                $assign['franchise_id'] = $franchise[$key];
                            }
                            $assign['pincode_id'] = $value;
                            $assign['type'] = $type;
                            $assign['assigned_date'] = date('Y-m-d');
                            $assign['created_at'] = date('Y-m-d H:i:s');
                            $this->master_db->insertRecord('assign_franchise',$assign);
                        }
                        $array = ['status'=>true,'msg'=>'Franchise assigned successfully','csrf_token'=> $this->security->get_csrf_hash()];
                      }
                   }
                }else {
                    $array['status'] = false;
                    $array['msg'] = "Pincode not exists in inventory to assign";
                    $array['csrf_token'] = $this->security->get_csrf_hash();
                }
           }else {
                $array = array(
                    'formerror'   => false,
                    'pincodes_error' => form_error('pincode[]'),
                    'franchise_error' => form_error('franchise[]'),
                    'csrf_token'=> $this->security->get_csrf_hash()
                   );
           }
           echo json_encode($array);
    }
    public function getFranchisepincodes() {
        //echo "<pre>";print_r($_POST);exit;
         $pincodes = $this->input->post('pincodes');
        if(is_array($pincodes)) {
              $pinid =  implode(',',$pincodes[0]);
            $getpincodes = $this->master_db->sqlExecute('select f.franchise_id, CONCAT(f.franchise_name," - ",f.address," - ",f.contact_no," - ",f.area," - ",f.subarea," - ",f.street) as franchise_name from franchises f left join franchises_pincodes fp on fp.franchise_id = f.franchise_id where f.status =0 and fp.pincode_id in ('.$pinid.') group by fp.franchise_id ');
            $data = "";
            if(count($getpincodes) >0) {
                $data .="<option value=''>Select Franchise</option>";
                foreach ($getpincodes as $key => $value) {
                    $data .="<option value='".$value->franchise_id."'>".$value->franchise_name."</option>";
                }
            }
        }
        echo $data;
    }
    public function fetchFranchisepincode() {
    	$id = $this->input->post('id');
        $getfranchise = $this->master_db->sqlExecute('select fp.id,p.pincode from franchises_pincodes fp left join pincodes p on p.id = fp.pincode_id where fp.franchise_id='.$id.'');
        //echo $this->db->last_query();exit;
        if(count($getfranchise) >0) {
             $this->data['getfranchise'] = $getfranchise;
            $html = $this->load->view('masters/franchises/franchisespincodeview',$this->data,true);
            $result['status']=true;
            $result['data']=$html;
        }else {
              $result['status']=false;
            $result['data']="<h4>No pincode found</h4>";
        }
        echo json_encode($result);
    }
    public function viewFranchiselistview() {
        $id = $this->input->post('id');
        $getFranchise = $this->master_db->getRecords('franchises',['franchise_id'=>$id],'*');
        $getPincodes = $this->master_db->sqlExecute('select p.pincode from  franchises_pincodes fp left join pincodes p on p.id = fp.pincode_id where fp.franchise_id='.$id.'');
        //echo $this->db->last_query();exit;
        $this->data['franchise'] =  $getFranchise;
        $this->data['franchise_pincode'] =  $getPincodes;
       $html = $this->load->view('masters/franchises/viewFranchiselistview',$this->data,true);
       echo json_encode(['status'=>true,'data'=>$html]);
    }
    public function getCustomeraddress() {
        $id =$this->input->post('id');
        $getOrders = $this->master_db->getRecords('order_bills',['oid'=>$id],'CONCAT(baddress," - ",bpincode) as address');
        if(count($getOrders) >0) {
            $result['status'] = true;
            $result['address'] = "Customer Address : ".$getOrders[0]->address;
        }else {
            $result['status'] = false;
            $result['msg'] = "No address found";
        }
        echo json_encode($result);
    }
    /****** referralpoints ********/
    public function referralpoints() {
        $this->load->view('masters/referralpoints/referralpoints',$this->data);
    }
    public function getreferralpointlist() {
        $where = "where r.wallet_id !=-1";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (o.name like '%$val%') ";
            $where.= " or (r.amount like '%$val%') ";
        }
        $order_by_arr[] = "r.referral_code";
        $order_by_arr[] = "";
        $order_by_arr[] = "r.wallet_id";
        $order_by_def = " order by r.wallet_id desc";
        $query = "select o.name,r.referral_code,r.amount from users o left join wallet r on r.user_id = o.u_id " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $sub_array = array();
            $sub_array[] = $i++;
            $sub_array[] = $r->name;
            $sub_array[] = $r->referral_code;
            $sub_array[] = "<i class='fas fa-indian-rupee'></i> ".$r->amount;
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function otpsend() {
        $this->load->library('SMS');
        $otp = rand(1234,9988);
        $phone ="9986571768";
        $message = "Dear User, Thank you for purchasing Swarnagowri products. Please share the OTP {#var#} while delivery. Regards Swarnagowri";
                            //echo $message;exit;
                                $message = str_replace('{#var#}',$otp,$message);
                            // echo $message;exit;
                                $this->sms->sendSmsToUser($message,$phone);
    }
    public function updatepincodewise() {
        echo "<pre>";print_r($_POST);
        $data = $this->input->post('pinupd');
        if(is_array($data) && !empty($data[0])) {
            foreach ($data as $key => $value) {
               $this->master_db->updateRecord('pincodes',['site_type'=>2],['id'=>$value]);
            }
            $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissable"><button class="close" aria-hidden="true" data-dismiss="alert" type="button">&times;</button>Pincode updated successfully</div>');
                redirect(base_url() . "pincodes");
        }else {
            redirect(base_url().'pincodes');
        }
    }
    /******* Qrcode **********/
    public function qrcode() {
        $this->load->view('masters/qrcode/qrcode', $this->data);
    }
    public function getqrcodelist() {
        $where = "where status !=2";
        if (isset($_POST["search"]["value"]) && !empty($_POST["search"]["value"])) {
            $val = trim($_POST["search"]["value"]);
            $where.= " and (id like '%$val%') ";
            $where.= " or (qrcodeimg like '%$val%') ";
        }
        $order_by_arr[] = "status";
        $order_by_arr[] = "";
        $order_by_arr[] = "id";
        $order_by_def = " order by id desc";
        $query = "select * from qrcodeimg " . $where . "";
        $fetchdata = $this->home_db->rows_by_paginations($query, $order_by_def, $order_by_arr);
        //echo $this->db->last_query();exit;
        $data = array();
        $i = $_POST["start"] + 1;
        foreach ($fetchdata as $r) {
            $action = "";
            $sub_array = array();
            $image = "";
            if (!empty($r->qrcodeimg)) {
                $image.= "<img src='" . app_url() . $r->qrcodeimg . "'  style='width:80px'/>";
            }
            $action = '<a href=' . base_url() . "master/editqrcode/" . icchaEncrypt(($r->id)) . "" . ' class="btn btn-primary" title="Edit Details"><i class="fas fa-pencil-alt"></i></a>&nbsp;';
            if ($r->status == 0) {
                $action.= "<button title='Deactive' class='btn btn-info' onclick='updateStatus(" . $r->id . ", 1)' ><i class='fas fa-times-circle'></i></button>&nbsp;";
            } else {
                $action.= "<button title='Active' class='btn btn-warning' onclick='updateStatus(" . $r->id . ", 0)' ><i class='fas fa-check'></i></button>&nbsp;";
            }
            $action.= "<button title='Delete' class='btn btn-danger' onclick='updateStatus(" . $r->id . ", 2)' ><i class='fas fa-trash'></i></button>&nbsp;";
            $sub_array[] = $i++;
            $sub_array[] = $action;
            $sub_array[] = $image;
            $data[] = $sub_array;
        }
        $res = $this->home_db->run_manual_query_result($query);
        $total = count($res);
        $output = array("draw" => intval($_POST["draw"]), "recordsTotal" => $total, "recordsFiltered" => $total, "data" => $data);
        $output['csrf_token'] = $this->security->get_csrf_hash();
        echo json_encode($output);
    }
    public function qrcodeadd() {
        $this->load->view('masters/qrcode/qrcodeadd', $this->data);
    }
    public function editqrcode() {
        $id = icchaDcrypt($this->uri->segment(3));
        //echo $id;exit;
        $this->data['category'] = $this->master_db->getRecords('qrcodeimg', ['id' => $id], '*');
        // echo $this->db->last_query();exit;
        $this->load->view('masters/qrcode/qrcodeedit', $this->data);
    }
    public function qrcodesave() {
        // echo "<pre>";print_r($_POST);print_r($_FILES);exit;
        $id = $this->input->post('cid');
            if ($id == "") {
                if (!empty($_FILES['image']['name'])) {
                    //unlink("$images");
                    $uploadPath = '../assets/qrcode/';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                    $ext = pathinfo($_FILES["image"]['name'], PATHINFO_EXTENSION);
                    $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                    $config['file_name'] = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('image')) {
                        $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                        redirect(base_url() . 'master/qrcodeadd');
                    } else {
                        $upload_data = $this->upload->data();
                        $db['qrcodeimg'] = 'assets/qrcode/' . $upload_data['file_name'];
                    }
                }
                $db['created_at'] = date('Y-m-d H:i:s');
                $in = $this->master_db->insertRecord('qrcodeimg', $db);
                if ($in > 0) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Inserted successfully</div>');
                    redirect(base_url() . 'qrcode');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in inserting</div>');
                    redirect(base_url() . 'master/qrcodeadd');
                }
            } else {
                $ids = $this->input->post('cid');
                //echo "<pre>";print_r($_POST);print_r($_FILES);exit;
                if (!empty($_FILES['image']['name'])) {
                    //unlink("$images");
                    $uploadPath = '../assets/qrcode/';
                    $config['upload_path'] = $uploadPath;
                    $config['allowed_types'] = 'jpg|png|PNG|JPEG|jpeg';
                    $ext = pathinfo($_FILES["image"]['name'], PATHINFO_EXTENSION);
                    $new_name = "SWARNA" . rand(11111, 99999) . '.' . $ext;
                    $config['file_name'] = $new_name;
                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
                    if (!$this->upload->do_upload('image')) {
                        $this->session->set_flashdata("message", "<div class='alert alert-danger'>" . $this->upload->display_errors() . "</div>");
                        redirect(base_url() . 'master/editqrcode');
                    } else {
                        $upload_data1 = $this->upload->data();
                        $db['qrcodeimg'] = 'assets/qrcode/' . $upload_data1['file_name'];
                    }
                }
                $db['modified_at'] = date('Y-m-d H:i:s');
                $update = $this->master_db->updateRecord('qrcodeimg', $db, ['id' => $id]);
                if ($update > 0) {
                    $this->session->set_flashdata('message', '<div class="alert alert-success alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Updated successfully</div>');
                    redirect(base_url() . 'qrcode');
                } else {
                    $this->session->set_flashdata('message', '<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert">&times;</button>Errro in updating</div>');
                    redirect(base_url() . 'qrcode');
                }
            }
        
    }
    public function setqrcodeStatus() {
        $id = trim($this->input->post('id'));
        //echo "<pre>";print_r($_POST);exit;
        $status = trim($this->input->post('status'));
        if ($status == 2) {
            $this->master_db->deleterecord('qrcodeimg', ['id' => $id]);
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 1) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('qrcodeimg', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        } else if ($status == 0) {
            $where_data = array('status' => $status, 'modified_at' => date('Y-m-d H:i:s'),);
            $this->master_db->updateRecord('qrcodeimg', $where_data, array('id' => $id));
            echo json_encode(['status' => 1, 'csrf_token' => $this->security->get_csrf_hash() ]);
        }
    }
    public function getVouchercountlist() {
        $prefix = $this->input->post('prefix');
        $vo = $this->input->post('vouc');
        if(!empty($prefix) && !empty($vo)) {
            $html ="<ul>";
            for($i =1;$i <=$vo;$i++) {
                $pr = $prefix.$i;
                $html .= "<li id='remove".$i."' style='margin-bottom:10px;'><input type='hidden' name='vouchercount[]' value='".$pr."'/>".$i.".".$pr."<button class='btn btn-danger' onclick='removevouchers(".$i.")' style='margin-left:10px;'>Remove</button></li>";
            }
            $html .="</ul>";
            echo json_encode(['status'=>true,'voucher'=>$html]);
        }
    }
    /********* Wallet settings **********/
    public function walletsetting() {
        $this->data['walletamount'] = $this->master_db->getRecords('wallet_amount', ['id' => 1], '*');
        $this->load->view('walletamount', $this->data);
    }
    public function walletsave() {
        $id = 1;
        $amount = trim($this->input->post('amount'));
      
        $db['amount'] = $amount;
        $db['modified_at'] = date('Y-m-d H:i:s');
        $update = $this->master_db->updateRecord('wallet_amount', $db, array('id' => $id));
       
            $this->session->set_flashdata('message', '<div class="alert alert-success">Updated successfully</div>');
            redirect('walletsetting');
        
    }
}
?>