<?php

namespace KnowledgeCity\Entities;

abstract class AbstractEntity {
    
    protected $id;

    public function __construct( $id, $field_values = array() ) {
		$this->id = $id;
		$this->setupFields( $field_values );
	}

    public function setupFields( $fieldValues ) {

		foreach ( $fieldValues as $field => $value ) {

			$setFieldMethod = 'set' . ucfirst( $field );

			if ( method_exists( $this, $setFieldMethod ) ) {

				$this->$setFieldMethod( $value );

			} else {
				$this->$field = $value;
			}
		}
	}

    public function setId( $id ) {
		$this->id = $id;
	}

    public function getId(){
        
        return $this->id;
        
    }
    
    public function toArray() {
		return get_object_vars($this);
	}
    
}