<?php

namespace Resoma\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ResomaUserBundle extends Bundle
{
	public function getParent()
    {
   	 return 'FOSUserBundle';
    }

}
