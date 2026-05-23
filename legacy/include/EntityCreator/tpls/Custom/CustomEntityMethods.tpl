    public function getMainEntity()
    {
        return $this->main_entity;
    }

    public function setMainEntity($main_entity)
    {
        $this->main_entity = $main_entity;

        if ($main_entity && $main_entity->getCustomEntity() !== $this) {
            $main_entity->setCustomEntity($this);
        }

        return $this;
    }

