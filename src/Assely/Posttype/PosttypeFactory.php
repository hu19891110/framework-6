<?php

namespace Assely\Posttype;

use Assely\Foundation\Factory;
use Assely\Contracts\Singularity\Model\ModelInterface;

class PosttypeFactory extends Factory
{
    /**
     * Create posttype.
     *
     * @param  \Assely\Contracts\Singularity\Model\ModelInterface $model
     *
     * @return \Assely\Posttype\PosttypeSingularity
     */
    public function create(ModelInterface $model)
    {
        $posttype = $this->container->make(PosttypeSingularity::class);

        $posttype
            ->setModel($model)
            ->boot();

        return $posttype;
    }
}
