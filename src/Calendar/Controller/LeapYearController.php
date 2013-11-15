<?php

namespace Calendar\Controller;
 
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Scarlett\Controller\Controller;
use Calendar\Model\LeapYear;
 
class LeapYearController extends Controller
{
    public function indexAction(Request $request, $year)
    {
        $leapyear = new LeapYear();
        if ($leapyear->isLeapYear($year)) {
            $message = 'Yep, this is a leap year!';
        }else{
            $message = 'Nope, this is not a leap year!';
        }
 
        return $this->render('leapYear', array('message' => $message));
    }

    public function primeAction(Request $request, $number)
    {
        $message = "It's prime!";
        for($i=$number-1;$i>=2;$i--) {
            if($number%$i == 0) {
                $message =" It's not prime!";
                break;
            }
        }

        return $this->render('primeNumber', array('message' => $message));
    }
}