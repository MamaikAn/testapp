<?php
/**
 * @version $Id: DocumentListRequest.php 91 2013-04-14 07:11:31Z slavb $
 */
/**
 * Входные данные тестового отчета
 * @xmlns urn:ru:ilb:meta:TestApp:DocumentListRequest
 * @xmlname DocumentListRequest
 * @codegen true
 */
class TestApp_DocumentListRequest extends Adaptor_XMLBase {
	/**
	 * Дата начала периода
	 *
	 * @var Basictypes_Date
	 */
	public $dateStart;
	/**
	 * Конец периода
	 *
	 * @var Basictypes_Date
	 */
	public $dateEnd;

	/**
	 * Ключевые слова
	 *
	 * @var array
	 */
	public $keywords = [];

	/**
	 * Удален
	 *
	 * @var boolean
	 */
	public $deleted = NULL;

	/**
	 * Наименование
	 *
	 * @var string
	 */
	public $name = NULL;

	/**
	 * Формат вывода
	 *
	 * @var string
	 */
	public $outputFormat="html";

	public function  __construct() {
		$this->dateStart=new Basictypes_Date("2000-01-01");
		$this->dateEnd=new Basictypes_Date();
	}
	/**
	 * Вывод в XMLWriter
	 * @codegen true
	 * @param XMLWriter $xw
	 * @param string $xmlname Имя корневого узла
	 * @param int $mode
	 */
	public function toXmlWriter(XMLWriter &$xw,$xmlname=NULL,$xmlns=NULL,$mode=Adaptor_XML::ELEMENT){
		$xmlname=$xmlname?$xmlname:"DocumentListRequest";
		$xmlns=$xmlns?$xmlns:"urn:ru:ilb:meta:TestApp:DocumentListRequest";
		if ($mode&Adaptor_XML::STARTELEMENT) $xw->startElementNS(NULL,$xmlname,$xmlns);
			if($this->dateStart!==NULL) {$xw->writeElement("dateStart",$this->dateStart->LogicalToXSD());}
			if($this->dateEnd!==NULL) {$xw->writeElement("dateEnd",$this->dateEnd->LogicalToXSD());}
			$this->writeKeywords($xw, $mode);
			$this->writeDeleted($xw, $mode);
			if($this->name!==NULL) {$xw->writeElement("name",$this->name);}
			if($this->outputFormat!==NULL) {$xw->writeElement("outputFormat",$this->outputFormat);}
		if ($mode&Adaptor_XML::ENDELEMENT) $xw->endElement();
	}
	/**
	 * Чтение из  XMLReader
	 * @codegen true
	 * @param XMLReader $xr
	 */
	public function fromXmlReader(XMLReader &$xr){
		while($xr->nodeType!=XMLReader::ELEMENT) $xr->read();
		$root=$xr->localName;
		if($xr->isEmptyElement) return $this;
		while($xr->read()){
			if($xr->nodeType==XMLReader::ELEMENT) {
				$xsinil=$xr->getAttributeNs("nil","http://www.w3.org/2001/XMLSchema-instance")=="true";
				switch($xr->localName){
					case "dateStart": $this->dateStart=$xsinil?NULL:new Basictypes_Date($xr->readString(),Adaptor_DataType::XSD); break;
					case "dateEnd": $this->dateEnd=$xsinil?NULL:new Basictypes_Date($xr->readString(),Adaptor_DataType::XSD); break;
					case "keywords": 
						if (!$xsinil) $this->keywords[] = $xr->readString();
						break;
					case "deleted": 
						$this->deleted=$xr->readString();
						$this->deleted = $this->deleted == 'null' ? NULL : $this->deleted;
						break;
					case "name": 
						if ($xsinil) $this->name = NULL;
						else {
							$name = $xr->readString();
							$this->name = empty($name) ? NULL : $name;
						}
						break;
					case "outputFormat": $this->outputFormat=$xsinil?NULL:$xr->readString(); break;
				}
			}elseif($xr->nodeType==XMLReader::END_ELEMENT&&$root==$xr->localName){
				return;
			}
		}
		return $this;
	}

	/**
	 * Вывод массива keywords в XMLWriter
	 * 
	 * @param XMLWriter $xw
	 * @param int $mode
	 * @param string $xmlname Имя корневого узла
	 */
	protected function writeKeywords(XMLWriter &$xw, $mode=Adaptor_XML::ELEMENT) {
		$pdo=new PDO("mysql:host=localhost;dbname=testapp;charset=utf8","testapp","1qazxsw2",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
		$options = array();
		foreach($pdo->query("SELECT DISTINCT keywords FROM document WHERE keywords <> ''")->fetchAll(PDO::FETCH_COLUMN) as $row) {
			foreach(explode(', ', $row) as $string) {
				if ($mode&Adaptor_XML::STARTELEMENT) $xw->startElement("keyword");
					if ($this->keywords && in_array($string, $this->keywords)) {
						$xw->writeAttribute("selected", "true");
					}
					$xw->text($string);
				if ($mode&Adaptor_XML::ENDELEMENT) $xw->endElement();
			}
		}
	}

	/**
	 * Вывод deleted в XMLWriter
	 * 
	 * @param XMLWriter $xw
	 * @param int $mode
	 * @param string $xmlname Имя корневого узла
	 */
	protected function writeDeleted(XMLWriter &$xw, $mode=Adaptor_XML::ELEMENT) {
		if ($mode&Adaptor_XML::STARTELEMENT) $xw->startElement("deleted");
			if ($this->deleted === "true") $xw->writeAttribute("checked", "true");
			$xw->writeAttribute("value", "true");
			$xw->text("да");
		if ($mode&Adaptor_XML::ENDELEMENT) $xw->endElement();

		if ($mode&Adaptor_XML::STARTELEMENT) $xw->startElement("deleted");
			if ($this->deleted === "false") $xw->writeAttribute("checked", "true");
			$xw->writeAttribute("value", "false");
			$xw->text("нет");
		if ($mode&Adaptor_XML::ENDELEMENT) $xw->endElement();

		if ($mode&Adaptor_XML::STARTELEMENT) $xw->startElement("deleted");
			if ($this->deleted === NULL) $xw->writeAttribute("checked", "true");
			$xw->writeAttribute("value", "null");
			$xw->text("да/нет");
		if ($mode&Adaptor_XML::ENDELEMENT) $xw->endElement();
	}
}
