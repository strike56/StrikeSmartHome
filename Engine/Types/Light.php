<?php

    namespace Engine\Types;

    use \Engine\Traits\OnOff;
    use \Engine\Traits\Brightness;
    use \Engine\Type;

     class Light extends Type {

         const TYPE = 'Light';

         use OnOff;
         use Brightness;
     }
