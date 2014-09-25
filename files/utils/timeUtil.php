<?php

 function FormatTime($timestamp)
{
     global $locale_domain;
    // Get time difference and setup arrays
    $difference = time() - $timestamp;
    $periods = array(_("second"), _("minute"), _("hour"), _("day"), _("week"), _("month"), _("years"));
    $lengths = array("60","60","24","7","4.35","12");

    // Past or present
    if ($difference >= 0) 
    {
        $ending = _("ago");
    }
    else
    {
        $difference = -$difference;
        $ending = "to go";
    }

    // Figure out difference by looping while less than array length
    // and difference is larger than lengths.
    $arr_len = count($lengths);
    for($j = 0; $j < $arr_len && $difference >= $lengths[$j]; $j++)
    {
        $difference /= $lengths[$j];
    }

    // Round up     
    $difference = round($difference);

    // Make plural if needed
    if($difference != 1 && $locale_domain != "he_IL") 
    {
        $periods[$j].= "s";
    }

    // Default format
    if ($locale_domain != "he_IL")
        $text = "$difference $periods[$j] $ending";
    else {
        $text = "$ending $difference $periods[$j]";
    }

    // over 24 hours
    if($j > 2)
    {
        // future date over a day formate with year
        if($ending == "to go")
        {
            if($j == 3 && $difference == 1)
            {
                $text = "Tomorrow at ". date("G:i", $timestamp);
            }
            else
            {
                $text = date("F j, Y G:i", $timestamp);
            }
            return $text;
        }

        if($j == 3 && $difference == 1) // Yesterday
        {
            $text = _("Yesterday");
            //$text = _("Yesterday at "). date("G:i", $timestamp);
        }
        else if($j == 3) // Less than a week display -- Monday at 5:28pm
        {
            //$text = date("l \a\\t G:i", $timestamp);
            $day    = date("l", $timestamp);
            $text   = $day;
        }
        else if($j < 6 && !($j == 5 && $difference == 12)) // Less than a year display -- June 25 at 5:23am
        {
            
            $month = _(date("F", $timestamp));
            if ($locale_domain != "he_IL")
            {
                $text = date("j", $timestamp);
                $text = $month . " " . $text;
            }
            else 
            {
                $day = date("j", $timestamp);
                $text = $day . " " . $month ;
            }
        }
        else // if over a year or the same month one year ago -- June 30, 2010 at 5:34pm
        {
            $text = date("F j, Y", $timestamp);
        }
    }

    return $text;
}
?>