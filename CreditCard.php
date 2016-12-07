<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CreditCard extends Model
{
    public $cardnumber;
    public $cv2;
    public $exp_month;
    public $exp_year;
    public $holder;
    public $info;
    public $validation_state;
    public $type;

    function __construct($cardnumber,$cv2,$exp_month,$exp_year,$holder)
    {
        $this->cardnumber=$cardnumber;
        $this->validation_state=false;
        $this->cv2=$cv2;
        $this->exp_month=$exp_month;
        $this->exp_year=$exp_year;
        $this->holder=$holder;
    }

    function CheckExpirationDate()
    {
        $result=false;

        try
        {
            $carbon=Carbon::today();
            $month=$carbon->month;
            $year=$carbon->year;
            $month=intval($month);
            $year=intval($year);

            if(intval($this->exp_year)>$year)
            {
                $result=true;
            }
            else if(intval($this->exp_year)==$year)
            {
                if(intval($this->exp_month)>=$month)
                {
                    $result=true;
                }
            }
        }
        catch (Exception $ex)
        {
            $result=false;
        }

        return $result;
    }
    function ValidateCVV()
    {
        $result=false;
        $indexes = str_split($this->cv2);
        $count=count($indexes);
        if($this->GetType()=="American Express")
        {
            if($count==4)
            {
                $result=true;
            }
        }
        else
        {
            if($count==3)
            {
                $result=true;
            }
        }
        return $result;
    }
    function GetType()
    {
        $result="";
        $code2digit="";
        $code4digit="";
        $indexes = str_split($this->cardnumber);

        if(count($indexes)>=15)
        {
            for($i=0;$i<2;$i++)
            {
                $code2digit.=$indexes[$i];
            }

            $code=intval($code2digit);
            if($code==34 || $code==37)
            {
                $result="American Express";
                $this->type=$result;
            }
            else if($code>50 && $code<56)
            {
                $result="Master Card";
                $this->type=$result;

            }
            else if($code<50 && $code>39)
            {
                $result="Visa";
                $this->type=$result;

            }
            else if($code==35)
            {
                $result = "JCB";
                $this->type=$result;

            }
            else if($code==65)
            {
                $result = "Discover";
                $this->type=$result;

            }
            else
            {
                for($i=0;$i<4;$i++)
                {
                    $code4digit.=$indexes[$i];
                }
                $code=intval($code4digit);

                if($code==6011)
                {
                    $result = "Discover";
                    $this->type=$result;

                }
                else if($code==2131 || $code==1800)
                {
                    $result = "JCB";
                    $this->type=$result;
                }
            }
        }
        else
        {
            $result="You entered mistake or missing number!";
        }

        return $result;
    }

    function isValid()
    {
        if($this->GetType($this->cardnumber)=="American Express")
        {
            $sumofodd=0;
            $sumofdouble=0;
            $str_doubles="";
            $indexes = str_split($this->cardnumber);
            if(count($indexes)==15)
            {
                $j=0;

                for($i=0;$i<count($indexes);$i++)
                {
                    if($i%2!=0)
                    {
                        $arrayDouble[$j]=intval($indexes[$i])*2;
                        $j++;
                    }
                    else
                    {
                        $sumofodd+=intval($indexes[$i]);
                    }
                }

                for($i=0;$i<count($arrayDouble);$i++)
                {
                    $str_doubles.=$arrayDouble[$i]."";
                }
                $doubles=str_split($str_doubles);

                for($i=0;$i<count($doubles);$i++)
                {
                    $sumofdouble+=intval($doubles[$i]);
                }

                if(($sumofodd+$sumofdouble)%10==0)
                {
                    $this->validation_state=true;
                    $this->info="Valid Card";
                }
                else
                {
                    $this->info="Invalid Card";
                }
            }
            else
            {
                $this->info="Your ".$this->GetType($this->cardnumber)." Must Have 15 Digits!";
            }

        }
        else if($this->GetType($this->cardnumber)=="Visa" || $this->GetType($this->cardnumber)=="Master Card" || $this->GetType($this->cardnumber)=="JCB" || $this->GetType($this->cardnumber)=="Discover")
        {

            $info="";
            $sumofodd=0;
            $sumofdouble=0;
            $str_odds="";
            $indexes = str_split($this->cardnumber);

            if(count($indexes)==16)
            {
                $j=0;

                for($i=0;$i<count($indexes);$i++)
                {
                    if($i%2==0)
                    {
                        $arrayODD[$j]=intval($indexes[$i])*2;
                        $j++;
                    }
                    else
                    {
                        $sumofdouble+=intval($indexes[$i]);
                    }
                }

                for($i=0;$i<count($arrayODD);$i++)
                {
                    $str_odds.=$arrayODD[$i]."";
                }
                $odds=str_split($str_odds);

                for($i=0;$i<count($odds);$i++)
                {
                    $sumofodd+=intval($odds[$i]);
                }

                if(($sumofodd+$sumofdouble)%10==0)
                {
                    $this->info="Valid Card";
                    $this->validation_state=true;
                }
                else
                {
                    $this->info="Invalid Card";
                }
            }
            else
            {
                $this->info="Your ".$this->GetType($this->cardnumber)." Must Have 15 Digits!";
                $this->validation_state=false;
            }

        }
        else
        {
            $this->info="Unsupported Card Type. ".$this->GetType($this->cardnumber);
            $this->validation_state=false;
        }

    }
}
