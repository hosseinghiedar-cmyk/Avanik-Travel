<?php
namespace Avanik;
if (!defined('ABSPATH')) exit;

/** Safe Elementor widget registrar. Kept separate so Elementor remains optional. */
final class ElementorWidgetFix {
    public static function boot(): void { add_action('elementor/widgets/register',[self::class,'register']); }
    public static function register($manager): void {
        if (!class_exists('\\Elementor\\Widget_Base')) return;
        $items=[
            ['avanik_search','آوانیک — جستجوی پرواز و تور','eicon-search'],
            ['avanik_hero','آوانیک — Hero / معرفی','eicon-banner'],
            ['avanik_services','آوانیک — خدمات','eicon-apps'],
            ['avanik_destinations','آوانیک — مقصدهای محبوب','eicon-map-pin'],
            ['avanik_why','آوانیک — چرا ما؟','eicon-star'],
            ['avanik_login','آوانیک — ورود / ثبت‌نام','eicon-lock-user'],
        ];
        foreach($items as $item){
            [$name,$title,$icon]=$item;
            $manager->register(new class($name,$title,$icon) extends \Elementor\Widget_Base {
                private $n; private $t; private $i;
                public function __construct($n,$t,$i,$data=[],$args=null){$this->n=$n;$this->t=$t;$this->i=$i;parent::__construct($data,$args);}
                public function get_name(){return $this->n;}
                public function get_title(){return $this->t;}
                public function get_icon(){return $this->i;}
                public function get_categories(){return ['basic'];}
                protected function register_controls(){
                    if($this->n==='avanik_search'){
                        $this->start_controls_section('avanik_search_controls',['label'=>'آوانیک']);
                        $this->add_control('compact',['label'=>'حالت فشرده','type'=>\Elementor\Controls_Manager::SWITCHER,'return_value'=>'yes']);
                        $this->end_controls_section();
                    }
                    if($this->n==='avanik_hero'){
                        $this->start_controls_section('avanik_hero_controls',['label'=>'محتوای Hero']);
                        foreach([['eyebrow','متن بالایی','سفر بعدی شما از اینجا شروع می‌شود'],['title','عنوان','پرواز به'],['accent','کلمه برجسته','استانبول'],['subtitle','زیرعنوان','با بهترین قیمت و خدمات ویژه']] as $c)$this->add_control($c[0],['label'=>$c[1],'type'=>\Elementor\Controls_Manager::TEXT,'default'=>$c[2]]);
                        $this->end_controls_section();
                    }
                }
                protected function render(){
                    $s=$this->get_settings_for_display();
                    if($this->n==='avanik_search') echo \Avanik\ElementorIntegration::shortcode_search(['compact'=>$s['compact']??'']);
                    elseif($this->n==='avanik_hero') echo \Avanik\ElementorIntegration::shortcode_hero(['eyebrow'=>$s['eyebrow']??'','title'=>$s['title']??'','accent'=>$s['accent']??'','subtitle'=>$s['subtitle']??'']);
                    else echo do_shortcode('['.$this->n.']');
                }
            });
        }
    }
}
