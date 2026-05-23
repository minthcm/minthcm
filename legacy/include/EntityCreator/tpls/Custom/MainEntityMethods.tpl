    public function getCustomEntity()
    {
        return $this->custom_entity;
    }


    public function setCustomEntity($custom_entity)
    {
        $this->custom_entity = $custom_entity;

        if ($custom_entity && $custom_entity->getMainEntity() !== $this) {
            $custom_entity->setMainEntity($this);
        }

        return $this;
    }

    