<?php

	// a upload model extension that let's to automatize the uploading changin some 
	// basic behaviour of the original model
	// this model let's you use it without the need of extending it manually

class Upload_standalone_model extends Upload_model{
	
	public $tableName = FALSE;
	public $directory = FALSE;
	public $className = FALSE;
	public $rules	  = FALSE;
	
	function __construct($config = array())
	{
		$this->load->library('upload');

		$this->load->library('cy_upload/Cy_uploader', $config);

	}
	
	protected function _load_classData()
	{

		$this->check_parameters();

		$query = beep_from('upload_classes')->where('class', $this->className)->get();

		if($query->num_rows() == 0)
		{
			throw new Exception("No se ha definido una clase de upload válida.", 1);

		}

		$row = $query->row(0, 'array');

		$this->classData = $row;
	}
	
	// checks if all the global values of the method are initialized
	
	protected function _check_parameters()
	{
		if($this->tableName === FALSE or $this->directory === FALSE or $this->className === FALSE or $this->rules === FALSE)
		{
			throw new Exception("Error initializating Cy_form_upload_model", 1);
		}
	}
	
	//rules que servirán para cy_uploader y form_validation
	//forma recomendada de mostrarlas
	/*		return array( array('field' => 'files',
	 									   'label' => 'la foto',
	  									   'rules' => 'required|file_size_max['.$this->config->item('max_size_photo').'KB]|file_allowed_type[image]|file_image_maxdim['.$this->config->item('max_width_photo').','.$this->config->item('max_height_photo').']|file_image_mindim['.$this->config->item('min_width_photo').','.$this->config->item('min_height_photo').']|xss_clean')
	 							)
                        );

        si es un multiupload hay que poner las rules como las del upload de codeigniter
        $config['upload_path'] = './uploads/procesar';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '100';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
	 */
	public function rules()
	{
		return $this->rules;
	}

	
	// Loads the options like tableName, directory, etc... from a Correcaminos object
	// useful when we want to automatize the uploading of some files that doesn't need
	// any special insert or update
	
	function load_options_from_object($object_info)
	{

		$this->tableName = $object_info['tableName'];
		$this->directory = $object_info['directory'];
		$this->className = $object_info['className'];
		$this->rules	 = $object_info['rules'];
		
		$this->_load_classData();
	}
}
