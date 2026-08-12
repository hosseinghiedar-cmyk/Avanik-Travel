<?php
namespace Avanik;
defined('ABSPATH') || exit;

final class Jalali {
  private static function div(int $a,int $b): int { return intdiv($a,$b); }
  public static function gregorian_to_jalali(int $gy,int $gm,int $gd): array {
    $gdm=[0,31,59,90,120,151,181,212,243,273,304,334];
    $gy2=$gm>2?$gy+1:$gy;
    $days=355666+(365*$gy)+self::div($gy2+3,4)-self::div($gy2+99,100)+self::div($gy2+399,400)+$gd+$gdm[$gm-1];
    $jy=-1595+33*self::div($days,12053); $days%=12053;
    $jy+=4*self::div($days,1461); $days%=1461;
    if($days>365){$jy+=self::div($days-1,365);$days=($days-1)%365;}
    if($days<186){$jm=1+self::div($days,31);$jd=1+($days%31);}else{$jm=7+self::div($days-186,30);$jd=1+(($days-186)%30);}
    return [$jy,$jm,$jd];
  }
  public static function jalali_to_gregorian(int $jy,int $jm,int $jd): array {
    $jy+=1595;$days=-355668+(365*$jy)+self::div($jy,33)*8+self::div(($jy%33)+3,4)+$jd;
    if($jm<7)$days+=($jm-1)*31;else $days+=(($jm-7)*30)+186;
    $gy=400*self::div($days,146097);$days%=146097;
    if($days>36524){$gy+=100*self::div(--$days,36524);$days%=36524;if($days>=365)$days++;}
    $gy+=4*self::div($days,1461);$days%=1461;
    if($days>365){$gy+=self::div($days-1,365);$days=($days-1)%365;}
    $gd=$days+1;$leap=(($gy%4===0&&$gy%100!==0)||$gy%400===0);$gdm=[0,31,($leap?29:28),31,30,31,30,31,31,30,31,30,31];
    $gm=1;while($gm<=12&&$gd>$gdm[$gm]){$gd-=$gdm[$gm];$gm++;}return [$gy,$gm,$gd];
  }
  public static function format(int $timestamp=0,string $separator='/'): string {
    $timestamp=$timestamp?:current_time('timestamp');[$jy,$jm,$jd]=self::gregorian_to_jalali((int)wp_date('Y',$timestamp),(int)wp_date('n',$timestamp),(int)wp_date('j',$timestamp));
    return sprintf('%04d%s%02d%s%02d',$jy,$separator,$jm,$separator,$jd);
  }
}
