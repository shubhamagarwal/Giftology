<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');

/**
 * Products Controller
 *
 * @property Product $Product
 */
class ProductsController extends AppController {
    public $helpers = array('Minify.Minify');   
    public $paginate = array(
        'limit' => 100,
        'order' => array(
            'Product.display_order' => 'asc'
        )
    );
     public $presetVars = array(
            array('field' => 'id', 'type' => 'value'),
            array('field' => 'min_price', 'type' => 'value'),
            array('field' => 'max_price', 'type' => 'value'),
            array('field'=> 'min_value','type'=>'value'),
            array('field'=> 'code_type_id','type'=>'value'),
            array('field'=> 'code','type'=>'value'),
            array('field'=> 'vendor_id','type'=>'value'),
            array('field'=> 'product_type_id','type'=>'value'),
            array('field'=> 'gender_segment_id','type'=>'value'),
            array('field'=> 'city_segment_id','type'=>'value'),
            array('field'=> 'age_segment_id','type'=>'value'),
            array('field'=> 'display_order','type'=>'value'),

            array('field'=> 'created','type'=>'value'),
            array('field'=> 'modified','type'=>'value'),
        
        );
    public $uses = array( 'Product','User','UserAddress','Gift','UploadedProductCode','City', 'CitySegment', 'LocationSegment', 'ProductCitySegment');
    public $components = array('AesCrypt','Search.Prg');
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('send_product_expiry_reminder');
    }

    public function isAuthorized($user) {
        if (($this->action == 'view_products') || ($this->action == 'view_product')) {
            return true;
        }
        return parent::isAuthorized($user);
    }
    //WEB SERVICES
    public function ws_list () {
        $receiver_fb_id = isset($this->params->query['receiver_fb_id']) ? $this->params->query['receiver_fb_id'] : null;
        $e = $this->wsListProductsException($receiver_fb_id );
        if(isset($e) && !empty($e)) $this->set('products', array('error' => $e));
        else{
            $this->Product->recursive = 0;
            $conditions = array();
            $conditions['Product.display_order >'] = 0;
            if(PAID_PRODUCT_DISABLED)
                $conditions['Product.min_price'] = 0;
            $this->set('receiver_id', isset($this->request->params['named']['receiver_id']) ? $this->request->params['named']['receiver_id'] : null);
            $products_temp = $this->Product->find('all', array('conditions' => $conditions));
            $products = $this->product_filter($products_temp, $receiver_fb_id);
            $this->set('products', $products);
            unset($products, $products_temp);
        }
        $this->set('_serialize', array('products'));
    }
/**
 * index method
 *
 * @return void
 */
public function download_user_csv_all($download_selected = null){
        $this->Prg->commonProcess('Product');
        $array1 = unserialize($download_selected);
        $conditions= array('conditions' => array($this->Product->parseCriteria($array1)),'order'=>array('Product.modified'=>'DESC'));
        $result1= $this->Product->find('all', $conditions);

          $filename = "Products ".date("Y.m.d").".csv";
                    $csv_file = fopen('php://output', 'w');
                    header('Content-type: application/csv');
                    header('Content-Disposition: attachment; filename="'.$filename.'"');
                    $header_row= array('Id','Min Price','Max Price','Min Value','Days Valid','Code Type','Code','Vendor','Product Type','Gender Segment','City Segment','Age Segment','Display Order','Created','Modified');
                    fputcsv($csv_file,$header_row,',','"');
                    if( !empty( $this->data ))
                    {
                        foreach($result1 as $id)  
                        {
                            
                            $result= $this->Product->find('first', array('conditions'=>array('Product.id'=>$id['Product']['id'])));
                            $row = array(
                            $result['Product']['id'],
                            $result['Product']['min_price'],
                            $result['Product']['max_price'],
                            $result['Product']['min_value'],
                            $result['Product']['days_valid'],
                            $result['Product']['code_type_id'],
                            $result['Product']['code'],
                            $result['Product']['vendor_id'],
                            $result['Product']['product_type_id'],
                            $result['Product']['gender_segment_id'],
                            $result['Product']['city_segment_id'],
                            $result['Product']['age_segment_id'],
                            $result['Product']['display_order'],
                            $result['Product']['created'],
                            $result['Product']['modified'],

                             );
                            fputcsv($csv_file,$row,',','"');
                        }
                    }
                    die;

}
 public function download_product_csv(){
       
          $filename = "Products ".date("Y.m.d").".csv";
                    $csv_file = fopen('php://output', 'w');
                    header('Content-type: application/csv');
                    header('Content-Disposition: attachment; filename="'.$filename.'"');
                    $header_row= array('Id','Min Price','Max Price','Min Value','Days Valid','Code Type','Code','Vendor','Product Type','Gender Segment','City Segment','Age Segment','Display Order','Created','Modified');
                    fputcsv($csv_file,$header_row,',','"');
                    if( !empty( $this->data ))
                    {
                        foreach($this->data['chk1'] as $id)  
                        {
                            $ab=" ";
                            $result= $this->Product->find('first', array('conditions'=>array('Product.id'=>$id)));
                            $row = array(
                            $result['Product']['id'],
                            $result['Product']['min_price'],
                            $result['Product']['max_price'],
                            $result['Product']['min_value'],
                            $result['Product']['days_valid'],
                            $result['Product']['code_type_id'],
                            $result['Product']['code'],
                            $result['Product']['vendor_id'],
                            $result['Product']['product_type_id'],
                            $result['Product']['gender_segment_id'],
                            $result['Product']['city_segment_id'],
                            $result['Product']['age_segment_id'],
                            $result['Product']['display_order'],
                            $result['Product']['created'],
                            $result['Product']['modified'],

                             );
                            fputcsv($csv_file,$row,',','"');
                        }
                    }
                    die;
    }
     public function index() {
        $this->Prg->commonProcess('Product');
     if(($this->passedArgs['created_start'])||($this->passedArgs['modified_start']))
        { 
            if(!($this->passedArgs['created_start'])){
                 $modified_end=$this->passedArgs['modified_end'].' 23:59:59';
                 $modified_start=$this->passedArgs['modified_start'].' 00:00:00';
                if(!$this->passedArgs['modified_end']){
                    $modified_end=$this->passedArgs['modified_start'].' 23:59:59';
                }
                
               $conditions=array('conditions' => array($this->Product->parseCriteria($this->passedArgs),'Product.modified >'=>$modified_start,'Product.modified <' => $modified_end
               
               ),'order'=>array('Product.modified'=>'DESC')); 
            }
            
            if(!($this->passedArgs['modified_start'])){
                 $created_end=$this->passedArgs['created_end'].' 23:59:59';
                 $created_start=$this->passedArgs['created_start'].' 00:00:00';
                if(!$this->passedArgs['created_end']){
                    $created_end=$this->passedArgs['created_start'].' 23:59:59';
                }
             $conditions=array('conditions' => array($this->Product->parseCriteria($this->passedArgs) ,'Product.created >'=>$created_start,'Product.created <' => $created_end
               ),'order'=>array('Product.modified'=>'DESC')); 
            }


        
           
           if(($this->passedArgs['created_start'])&&(($this->passedArgs['modified_start'])) )
            { 
                 $modified_end=$this->passedArgs['modified_end'].' 23:59:59';
                 $modified_start=$this->passedArgs['modified_start'].' 00:00:00';
                 $created_end=$this->passedArgs['created_end'].' 23:59:59';
                 $created_start=$this->passedArgs['created_start'].' 00:00:00';
                if(!$this->passedArgs['modified_end']){
                    $modified_end=$this->passedArgs['modified_start'].' 23:59:59';
                }
                if(!$this->passedArgs['created_end']){
                    $created_end=$this->passedArgs['created_start'].' 23:59:59';
                }
                
          $conditions=array('conditions' => array($this->Product->parseCriteria($this->passedArgs),'Product.modified >'=>$modified_start,'Product.modified <' => $modified_end
           ,'Product.created >'=>$created_start,'Product.created <' => $created_end
            ),'order'=>array('Product.modified'=>'DESC'));  
             }  
            
    
        }
        else{
            $conditions= array('conditions' => array($this->Product->parseCriteria($this->passedArgs)),'order'=>array('Product.modified'=>'DESC'));

        }
        
        $vendors = $this->Product->Vendor->find('list');
        $Product_Type = $this->Product->ProductType->find('list');
        $Code_Type = $this->Product->CodeType->find('list');
        $City_Segment = $this->Product->CitySegment->find('list');
        $Age_Segment = $this->Product->AgeSegment->find('list');

        $this->Product->recursive = 0;
        //$conditions= array('conditions' => array($this->Product->parseCriteria($this->passedArgs)));
        $this->paginate = $conditions;
        $this->set('receiver_id', isset($this->request->params['named']['receiver_id']) ? $this->request->params['named']['receiver_id'] : null);
        $this->set('products', $this->paginate());
        $this->set('product_type',$Product_Type);
        $this->set('code_type',$Code_Type);
        $this->set('vendors',$vendors) ;
        $this->set('city_segment',$City_Segment) ;
        $this->set('age_segment',$Age_Segment) ;
        $this->set('download_selected',serialize($this->passedArgs));

    }
/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function view($id = null) {
        $this->Product->id = $id;
        if (!$this->Product->exists()) {
            throw new NotFoundException(__('Invalid product'));
        }
        $this->set('receiver_id', $this->request->params['named']['receiver_id']);
        $this->set('product', $this->Product->read(null, $id));
    }

/**
 * add method
 *
 * @return void
 */
    public function add() {
        if ($this->request->is('post')) {
            if($this->data['Product']['terms'] == "" || $this->data['Product']['redeem_instr'] == "" )
            {
                $this->Session->setFlash(__('Please enter the T&C and Redeem Instructions.'));
                    $this->redirect(array('controller' => 'products', 'action'=>'add'));   
            }
            $this->Product->create();
            $this->request->data['Product']['city_segment'] = serialize($this->request->data['city_segment']);
            unset($this->request->data['city_segment']);
            if($this->Product->save($this->request->data)) {
                $this->product_city_segments($product_id, $this->request->data['Product']['city_segment']);
                $this->Session->setFlash(__('The product has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The product could not be saved. Please, try again.'));
            }
        }
        $vendors = $this->Product->Vendor->find('list');
        $productTypes = $this->Product->ProductType->find('list');
        $codeTypes = $this->Product->CodeType->find('list');
        $genderSegments = $this->Product->GenderSegment->find('list');
        $ageSegments = $this->Product->AgeSegment->find('list');
        $citySegments = $this->Product->CitySegment->find('list');
        $this->set(compact('vendors', 'productTypes', 'genderSegments', 'ageSegments', 'citySegments', 'codeTypes'));
    }

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function edit($id = null) {
        $this->Product->id = $id;
        if (!$this->Product->exists()) {
            throw new NotFoundException(__('Invalid product'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Product']['city_segment'] = serialize($this->request->data['city_segment']);
            unset($this->request->data['city_segment']);
            if ($this->Product->save($this->request->data)) {
                $this->update_product_city_segments($id, $this->request->data['Product']['city_segment']);
                $this->Session->setFlash(__('The product has been saved'));
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash(__('The product could not be saved. Please, try again.'));
            }
        } else {
            $this->request->data = $this->Product->read(null, $id);
        }
        $codeTypes = $this->Product->CodeType->find('list');
        $vendors = $this->Product->Vendor->find('list');
        $productTypes = $this->Product->ProductType->find('list');
        $genderSegments = $this->Product->GenderSegment->find('list');
        $ageSegments = $this->Product->AgeSegment->find('list');
        $citySegments = $this->Product->CitySegment->find('list');
        $this->set(compact('vendors', 'productTypes', 'genderSegments', 'ageSegments', 'citySegments', 'codeTypes'));   }

/**
 * delete method
 *
 * @throws MethodNotAllowedException
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
    public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException(__('Invalid product'));
		}
		if ($this->Product->delete()) {
			$this->Session->setFlash(__('Product deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Product was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
	
    public function send_product_expiry_reminder($date = null, $days = null){
        //this function return product id which is going to expire after 30 days.
        $reminder_for_expire_product_id = array();
        $this->Product->unbindModel(array('hasMany' => array('Gift','UploadedProductCode'), 
            'belongsTo' => array('ProductType','GenderSegment','AgeSegment','CodeType','Gift')));
        $product_id[]=array();
        $email_product_id=array();
        $product_array1= $this->Product->find('all',array('fields'=>array('Product.id','Product.days_valid','Vendor.name')));
        foreach($product_array1 as $product)
        {
            $product_expire_date= $this->Product->UploadedProductCode->find('first',array('fields'=>array('UploadedProductCode.expiry'),'conditions' => array('UploadedProductCode.product_id' => $product['Product']['id'], 'UploadedProductCode.available' => 1)));
            if(isset($days)) $days_before_mail = $days;
            else $days_before_mail="30";
            $product_expire_date_minus_days_valid=date('Y-m-d', strtotime('-'.$product['Product']['days_valid'].'days', strtotime($product_expire_date['UploadedProductCode']['expiry'])));
            $product_expire_date_minus_days_valid_minus_thirty=date('Y-m-d', strtotime('-'.$days_before_mail.'days', strtotime($product_expire_date_minus_days_valid)));
            if($date) $expiry_date_start_range = $date;
            else $expiry_date_start_range = date('Y-m-d');
            if($product_expire_date_minus_days_valid_minus_thirty == $expiry_date_start_range)
            {
                $email_product_id[]=array($product['Product']['id'],$product['Vendor']['name']) ; 
            }
        }
        if (!empty($email_product_id))
        {
            $file =fopen(ROOT.'/app/tmp/product_code_expire_reminder.csv', 'w');
            fputcsv($file,array('Product Id','Vendor Name'));
            foreach ($email_product_id as $l)
            { 
                fputcsv($file,$l);
            }
            fclose($file);
            $email = new CakeEmail();
            $email->config('smtp')
            ->template('code_expire_reminder') 
            ->emailFormat('html')
            ->to(GIFT_CODE_EXPIRY_REMINDER_EMAIL)
            ->from(array('care@giftology.com' => 'Giftology'))
            ->attachments(ROOT.'/app/tmp/product_code_expire_reminder.csv') 
            ->subject('Products Code Expire Reminder')
            ->viewVars(array('name' => $this->Connect->user('name')))
            ->send();
        }
        if(isset($date) && isset($days))
            echo "List of product to be expired has been generated. Please check your mail ".GIFT_CODE_EXPIRY_REMINDER_EMAIL.".";
        $this->autoRender = $this->autoLayout = false;
    }
    public function view_products () {
        if(!$this->request->is('post'))
        {
            $this->Session->setFlash(__('No friends was selected. Please select a friend'));
            $this->redirect(array('controller' => 'reminders', 'action'=>'view_friends'));

        }
                     
        if($this->request->is('post')){

            $receiver_id=$this->AesCrypt->decrypt($this->data['friend_id']);
            $receiver_name=$this->data['friend_name'];
            $receiver_birthday=$this->data['friend_birth'];
            $receiver_location=$this->data['friend_loc'];
            $rcvrbirth_year=$this->data['friend_year'];
            $receiver_gender=$this->data['friend_sex'];
            $ocasion=$this->data['friend_ocasion'];
            $suggested=$this->data['friend_suggested'];
            
        }
       
        
        $location=isset($receiver_location) ? $receiver_location : NULL;
        $gender=isset($receiver_gender) ? $receiver_gender : NULL;
        $year = isset($rcvrbirth_year) ? $rcvrbirth_year : NULL;
        $today = date("Y"); 
        $gender=strtoupper($gender);
        $age=$today-$year;
        
        //$products = array();
        $gender = $this->Product->GenderSegment->find('all',array('conditions' => array('GenderSegment.gender' => $gender)));
        $gender=isset($gender['0']['GenderSegment']['id']) ? $gender['0']['GenderSegment']['id'] : NULL;
        //$location=isset($location['0']['CitySegment']['id']) ? $location['0']['CitySegment']['id'] : NULL;
        $age = $this->Product->AgeSegment->find('all',array('conditions' => array('AgeSegment.max >' => $age,'AgeSegment.min <' => $age)));
        $age=isset($age['0']['AgeSegment']['id']) ? $age['0']['AgeSegment']['id'] : NULL;
        
        //$products_for_gender = array();
        //$products_for_gender = $this->Product->find('list', array('fields' => array('id'), 'conditons' => array('Product.gender_segment_id'  => array($gender,ALL_GENDERS))));
        
        $location = $this->City->find('first',array('conditions' => array('city' => $location)));
        $city_segments = $this->LocationSegment->find('list',array('fields' => array('city_segment_id'),'conditions' => array('city_id' => $location['City']['id'])));
        
        $products_for_location = array() ;
        if(isset($city_segments) && !empty($city_segments)) $products_for_location_conditions['city_segment_id'] = $city_segments;
        else $products_for_location_conditions = 1;
        $products_for_location = $this->ProductCitySegment->find('list', array('fields' => array('product_id'), 'conditions' => $products_for_location_conditions));
        
        //$products_for_age = array();
        //$products_for_age = $this->Product->find('list', array('fields' => array('id'), 'conditons' => array('Product.age_segment_id' => array($age,ALL_AGES))));
        //$products = array_unique(array_merge($products_for_age,$products_for_gender,$products_for_location));
        
        //$conditions = array();
        //$conditions['NOT'] = array('Product.display_order' => 0);
        //$conditions['Product.gender_segment_id'] = array($gender,ALL_GENDERS);
        //$conditions['Product.id'] = $products_for_location;
        //$conditions['Product.age_segment_id'] = array($age,ALL_AGES);
        //$this->paginate['conditions'] = $conditions;
        $this->paginate['conditions']  = array('OR' => array('Product.id' => $products_for_location, array('NOT' => array('Product.display_order' => 0), 'Product.gender_segment_id'  => array($gender,ALL_GENDERS) ,'Product.city_segment_id' => array($location,ALL_CITIES) , 'Product.age_segment_id' => array($age,ALL_AGES))));
        $this->paginate['order']= 'Product.show_on_top,Product.min_price, Product.display_order ASC';
        $this->Product->recursive = 0;
       
        $product_array=$this->paginate();
        $proddd = $this->product_filter($product_array, $receiver_id);     
        foreach($proddd as $k => $product){
            $proddd[$k]['Product']['encrypted_gift_id'] = $this->AesCrypt->encrypt($product['Product']['id']);
        }
        $this->set('products',$proddd);

        //$this->paginate['conditions'] = array('Product.display_order >' => 0); //display_order = 0 is for disabled products
        $this->set('receiver_id', isset($receiver_id) ? $receiver_id : null);
        $this->set('receiver_name', isset($receiver_name) ? $receiver_name : null);
        $this->set('receiver_birthday', isset($receiver_birthday) ? $receiver_birthday : null);
        $this->set('ocasion', isset($ocasion) ? $ocasion : null);
        $this->set('suggested_friends', $suggested);
        $this->set('sender_id', $this->Auth->user('facebook_id'));
        //$this->set('products', $this->paginate());
        $this->set('title_for_layout', 'Send a gift voucher to '.(isset($receiver_name) ? $receiver_name : null));
        $this->Mixpanel->track('Viewing Product List ', array(
           'Receiver' => isset($receiver_name) ? 
            $receiver_name : null,
        ));    
    }
    public function view_product($id=null) {
        $t=time();
        $session_time=$this->Session->write('session_time', $t);
        $this->set('session_token',$this->AesCrypt->encrypt($t));

        if ($this->request->is('post')) {
            $receiver_id=$this->data['products']['receiver_id'];
            $receiver_name=$this->data['products']['receiver_name'];
            $receiver_birthday=$this->data['products']['receiver_birthday'];
            $ocasion=$this->data['products']['ocasion'];
            $id=$this->AesCrypt->decrypt($this->data['encrypted_product_id']);
            $suggested=$this->AesCrypt->decrypt($this->data['products']['suggested']);
        }
        $fb_id = $this->Auth->user('facebook_id');
        $rec_id = $this->User->find('first',array('fields'=>'User.id','conditions'=>array('User.facebook_id'=>$receiver_id)));
        $reciever_id_user_table=$rec_id['User']['id'];
        $reciever_address=$this->UserAddress->find('first',array('conditions'=>array('UserAddress.user_id'=>$reciever_id_user_table)));
        $this->set('id',$reciever_address['UserAddress']['id']);
        $this->set('address1',$reciever_address['UserAddress']['address1']);
        $this->set('address2',$reciever_address['UserAddress']['address2']);
        $this->set('city',$reciever_address['UserAddress']['city']);
        $this->set('pin_code',$reciever_address['UserAddress']['pin_code']);
        $this->set('phone',$reciever_address['UserAddress']['phone']);
        $this->set('state',$reciever_address['UserAddress']['state']);
        $this->set('country',$reciever_address['UserAddress']['country']);
        $this->set('reciever_email',$reciever_address['UserAddress']['reciever_email']);
        $this->set('suggested_friends',$suggested);


        $this->Product->id = $id;
        if (!$this->Product->exists()) {
            throw new NotFoundException(__('Invalid product'));
        }
        $this->set('facebook_id', $fb_id);
        $this->set('title_for_layout', "Send Gift"); // This is read in FacebookHelper to check for sending permissions on facebook. read that before changing XXHACK NS
        $this->set('receiver_id', isset($receiver_id) ? $receiver_id : null);
        $this->set('receiver_name', isset($receiver_name) ? $receiver_name : null);
        $this->set('receiver_birthday', isset($receiver_birthday) ? $receiver_birthday : null);
        $this->set('ocasion', isset($ocasion) ? $ocasion : null);
        $this->Product->contain(array('Vendor'));
        $proddd=$this->Product->read(null, $id);
        $proddd['Product']['encrypted_gift_id'] = $this->AesCrypt->encrypt($id);
        $this->set('product', $proddd);
        $this->Mixpanel->track('Viewing Product', array(
            'Receiver' => isset($receiver_name) ? 
                $receiver_name : null,
            'ProductId' => $id
            ));


    }

    public function wsListProductsException($receiver_fb_id){
        $error = array();
        if(!$receiver_fb_id) $error[1] = 'Receiver id is missing';
        return $error; 
    }

    public function product_filter($product_array, $receiver_fb_id){
        $show_product = array();
        $unpaid_product =array();
        $this->Gift->recursive = -1;
        foreach($product_array as $product)
        {  
            $product_id= NULL; 
            if($product['Product']['min_price']== 0)
            {
                $product_id=$product['Product']['id'];
                $sender_id = $this->Auth->user('id');
                $current_date= date("Y-m-d") ;
                $receiver_gift_limit  = $product['Product']['receiver_gift_limit'];
                $receiver_time_limit =$product['Product']['receiver_time_limit'];
                $receiver_id= isset($receiver_id) ? $receiver_id : NULL;
                $sender_gift_limit = $product['Product']['sender_gift_limit'];
                $sender_time_limit = $product['Product']['sender_time_limit'];
                $tomorrow = date("Y-m-d",mktime(0,0,0,date("m"),date("d")+1,date("Y")));
                $sender_end_date=date('Y-m-d', strtotime('-'.$sender_time_limit.'days', strtotime($tomorrow)));
                $receiver_end_date=date('Y-m-d', strtotime('-'.$receiver_time_limit.'days', strtotime($tomorrow)));
                /*$total_send_gift_acc_limit_sender = $this->Gift->query("select count(*)as cou from gifts where gift_status_id !=".GIFT_STATUS_TRANSACTION_PENDING." and created between '".$sender_end_date."' and '".$tomorrow."'
                    AND product_id = '".$product_id."'
                    AND sender_id = '".$sender_id."'
                ");*/

                $total_send_gift_acc_limit_sender = $this->Gift->find('count', array(
                    'conditions' => array(
                        'gift_status_id !=' => GIFT_STATUS_TRANSACTION_PENDING,
                        'created between' => $sender_end_date." and ".$tomorrow,
                        'product_id' => $product_id,
                        'sender_id' => $sender_id
                        )
                    ));

                $total_send_gift_acc_limit_sender = $this->Gift->find('count', array(
                    'conditions' => array(
                        'gift_status_id !=' => GIFT_STATUS_TRANSACTION_PENDING,
                        'created between' => $receiver_end_date." and ".$tomorrow,
                        'product_id' => $product_id,
                        'receiver_fb_id' => $receiver_fb_id
                        )
                    ));
                /*$total_gift_rec_acc_limit_receiver = $this->Gift->query("select count(*)as cou from gifts where gift_status_id !=".GIFT_STATUS_TRANSACTION_PENDING." and ccreated between '".$receiver_end_date."' and '".$tomorrow."'
                    AND product_id = '".$product_id."'
                    AND receiver_fb_id = '".$receiver_fb_id."'
                ");*/
                $vaild_till = NULL;
                $valid_till = date("Y-m-d", strtotime(date("Y-m-d")     . "+".$product['Product']['days_valid']." days"));
                $code_exists = $this->UploadedProductCode->find('count', array(
                    'conditions' => array(
                        'available'=>1, 
                        'product_id' =>$product_id,
                        'value' => $product['Product']['min_value'],
                        'expiry >' => $valid_till
                        )
                    ));
                if(($total_send_gift_acc_limit_sender < $sender_gift_limit))
                {
                    if(($total_gift_rec_acc_limit_receiver < $receiver_gift_limit))
                    {
                        if($code_exists){
                            $show_product[]=$product_id;
                        }
                    }
                }
            }
            else{
                $vaild_till = NULL;
                if(($product['Product']['min_value'] == $product['Product']['min_price']) &&  ($product['Product']['min_price'] == $product['Product']['max_price']) && ($product['Product']['min_value'] == $product['Product']['max_price']) 
                    && ($product['Product']['product_type_id' == DIGITAL]) && ($product['Product']['code_type_id'] == UPLOADED_CODE)){
                    $valid_till = date("Y-m-d", strtotime(date("Y-m-d")     . "+".$product['Product']['days_valid']." days"));
                    $code_exists = $this->UploadedProductCode->find('count', array(
                        'conditions' => array(
                            'available'=>1, 
                            'product_id' =>$product['Product']['id'],
                            'value' => $product['Product']['min_value'],
                            'expiry >' => $valid_till
                            )
                        ));
                }
                else{
                    $code_exists = TRUE;
                }

                if($code_exists){
                    $unpaid_product[]=$product['Product']['id'];
                }
            }
        }
           
        $free_paid_result = array_merge((array)$show_product, (array)$unpaid_product);
        $this->Gift->recursive = -1;
        $received_gifts = $this->Gift->find('all', array('fields' => array('DISTINCT product_id'),
            'conditions' => array('gift_status_id !=' => GIFT_STATUS_TRANSACTION_PENDING,'receiver_fb_id' => $receiver_fb_id,'expiry_date >' => date('Y-m-d'))));
        $gifts = array();
        foreach($received_gifts as $gift){
            $gifts[] = $gift['Gift']['product_id'];
        }

        $result = array_diff($free_paid_result, $gifts);

        //$products = $this->Product->find('all', array('conditions' => $conditions));
        $proddd=$this->Product->find('all', array('conditions' => array('Product.id' => $result),'order'=>array('Product.show_on_top','Product.min_price','Product.display_order')));
        return $proddd;
    }
    
    public function product_city_segments($product_id, $city_segment){
        $city_segments = unserialize($city_segment);
        $this->ProductCitySegment->create();
        $location_data = array();
        if(isset($city_segments) && !empty($city_segments)){
            $this->ProductCitySegment->create();
            foreach($city_segments as $k => $segment){
                $location_data[$k]['ProductCitySegment']['city_segment_id'] = $city_segment_id;
                $location_data[$k]['ProductCitySegment']['producgt_id'] = $segment;                    
            }
            $this->ProductCitySegment->saveMany($location_data);       
        }
    }
    
     public function update_product_city_segments($product_id, $city_data){
        $cities = unserialize($city_data);
        $cities_location_segment = $this->ProductCitySegment->find('list', array('fields' => array('city_segment_id'),'conditions'=> array('product_id' => $product_id)));
        $cities_deleted = array_diff($cities_location_segment,$cities);
        $cities_added = array_diff($cities, $cities_location_segment);
        $this->ProductCitySegment->create();
        $location_data = array();
        if(isset($cities_added) && !empty($cities_added)){
            foreach($cities_added as $k => $city){
                $location_data[$k]['ProductCitySegment']['city_segment_id'] = $city;
                $location_data[$k]['ProductCitySegment']['product_id'] = $product_id;                    
            }
            $this->ProductCitySegment->saveMany($location_data);    
        }
        
        if(isset($cities_deleted) && !empty($cities_deleted)){
            foreach($cities_deleted as $k => $cities){
                $this->ProductCitySegment->id = $k;
                $this->ProductCitySegment->delete();        
            }
        }
    }
    
}
