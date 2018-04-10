<?php
/**
 * This file is part of oc_bilemo project
 *
 * @author: Sébastien CHOMY <sebastien.chomy@gmail.com>
 * @since 2018/04
 */

namespace App\Services;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Translation\TranslatorInterface as Translator;

/**
 * Class ViewErrorsHelper
 *
 * @package App\Services
 */
class ViewErrorsHelper
{
    /** @var  Translator */
    protected $translator;

    /**
     * ViewErrorsHelper constructor.
     *
     * @param Translator $translator
     */
    public function __construct(
        Translator $translator
    ) {
        $this->translator = $translator;
    }

    /**
     * @param FormInterface $form
     *
     * @return array
     */
    public function getErrors(FormInterface $form)
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $this->translator->trans($error->getMessage(), [], 'validator');
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrors($childForm)) {
                    foreach ($childErrors as $ref => $error) {
                        $errors[$childForm->getName()] = $this->translator->trans($error, [], 'validator');
                    }
                }
            }
        }
        return $errors;
    }
}
