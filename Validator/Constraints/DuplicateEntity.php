<?php
/**
 * Created by PhpStorm.
 * User: siklol
 * Date: 29/12/14
 * Time: 3:48 PM
 */

namespace Scandio\TrobaBridgeBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DuplicateEntity extends Constraint
{
    public $message = "There already exists a duplicate entry";
    public $class;
    public $field = "name";
    public $checkId = true;

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'troba.duplicate_entity';
    }

    /**
     * @return array|string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
} 