<?php

declare(strict_types=1);

/*
 * (c) INSPIRED MINDS
 */

namespace InspiredMinds\ContaoPersonio\Event;

use Codefog\HasteBundle\Form\Form;
use Contao\ContentModel;
use Symfony\Contracts\EventDispatcher\Event;

class ModifyApplicationFormEvent extends Event
{
    public function __construct(
        private readonly Form $form,
        private readonly ContentModel $element,
    ) {
    }

    public function getForm(): Form
    {
        return $this->form;
    }

    public function getElement(): ContentModel
    {
        return $this->element;
    }
}
