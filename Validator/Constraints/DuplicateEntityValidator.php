<?php
/**
 * Created by PhpStorm.
 * User: siklol
 * Date: 29/12/14
 * Time: 3:50 PM
 */

namespace Scandio\TrobaBridgeBundle\Validator\Constraints;


use Scandio\TrobaBridgeBundle\Manager\TrobaManager;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use troba\EQM\Query;

class DuplicateEntityValidator extends ConstraintValidator
{
    /**
     * @var \Scandio\TrobaBridgeBundle\Manager\TrobaManager
     */
    private $em;

    public function __construct(TrobaManager $em)
    {
        $this->em = $em;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     *
     * @api
     */
    public function validate($object, Constraint $constraint)
    {
        $functionName = "get{$constraint->field}";
        if (!method_exists($object, $functionName)) {
            throw new \Exception(sprintf("Method %s does not exist!", $functionName));
        }
        /** @var Query $query */
        $query = $this->em->query($object);
        $query->where("{$constraint->field} = :{$constraint->field}", [$constraint->field => $object->$functionName()]);

        // if an id is present, check if there are duplicate entities in the db without itself
        if (method_exists($object, "getId") && $constraint->checkId) {
            $id = $object->getId();
            if (!empty($id)) {
                $query->andWhere("id != :id", ["id" => $id]);
            }
        }

        $duplicate = $query->one();

        $className = get_class($object);
        if ($duplicate instanceof $className) {
            $this->context->buildViolation($constraint->message)
                ->atPath($constraint->field)
                ->addViolation()
            ;
        }
    }
}