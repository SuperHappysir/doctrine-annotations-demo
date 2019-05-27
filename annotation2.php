<?php

set_exception_handler('my_exception');

require_once 'vendor/autoload.php';

use Doctrine\Common\Annotations\AnnotationReader;

/**
 * 如果您想定义自己的注释，只需将它们分组到一个名称空间中，并在AnnotationRegistry中注册这个名称空间
 * 注释类必须包含带有文本的类级docblock Annotation
 * @Annotation
 *
 * 表明注释类型适用于的类元素的类型，即注入注释允许的范围
 * 可选值 CLASS，PROPERTY，METHOD，ALL，ANNOTATION ，可选一个或者多个
 * 如果在当前上下文中不允许注释，则会得到一个AnnotationException
 * @Target({"METHOD","PROPERTY","CLASS"})
 *
 * 修饰属性
 * @Attributes({
 *   @Attribute("func", type="string"),
 * })
 */
class A
{
	public $value;

	/**
	 * @var string
	 */
	public $name;

	/**
	 * 注释解析器使用phpdoc注释 var 或 Attributes and Attribute 的组合，检查给定的参数
	 * 可选值 mixed，boolean，bool，float，string，integer，array，SomeAnnotationClass，array<integer>，array<SomeAnnotationClass>
	 * 如果数据类型不匹配，则会得到一个AnnotationException
	 * @var integer
	 */
	public $age;

	/**
	 * @var array
	 */
	public $sex;

	public $func;

	/**
	 * 定义为字段必填，否则报AnnotationException异常
	 * @Required
	 *
	 * 限制可选值，出错报AnnotationException异常
	 * @Enum({"NORTH", "SOUTH", "EAST", "WEST"})
	 */
	public $option;

	// 如果存在构造函数存在参数，则会把组值传给这个参数；反之，如果没有构造函数，则直接将值注入到公共属性
	public function __construct()
	{
	}
}

/**
 * @A("类注释")
 */
class B
{
	/**
	 * 属性注释
	 * @A(name="tom",age=18,sex={"male","fmale","secret"},option="NORTH")
	 */
	public $param;

	/**
	 * 方法注释
	 * @A(func="say")
	 */
	public function say()
	{

	}
}

$reflection = new ReflectionClass(B::class);
$reader = new AnnotationReader();

// 返回的是对象
$AClassAnnotation = $reader->getPropertyAnnotation($reflection->getProperty('param'), A::class);
// 返回的是对象组成的数组
//$AClassAnnotation = $reader->getPropertyAnnotations($reflection->getProperty('param'));

//$AClassAnnotation = $reader->getMethodAnnotation($reflection->getMethod('say'), A::class);

//$AClassAnnotation = $reader->getClassAnnotation($reflection, A::class);

dump($AClassAnnotation);

function my_exception($exception)
{
	echo '<div class="alert alert-danger">';
	echo '<b>Fatal error</b>:  Uncaught exception \'' . get_class($exception) . '\' with message ';
	echo $exception->getMessage() . '<br>';
	echo 'Stack trace:<pre>' . $exception->getTraceAsString() . '</pre>';
	echo 'thrown in <b>' . $exception->getFile() . '</b> on line <b>' . $exception->getLine() . '</b><br>';
	echo '</div>';
	exit();
}

function dump($data)
{
	echo '<pre>';
	var_dump($data);
	exit();
}
