<?php
/**
 * MoIP - Moip Payment Module
 *
 * @title      Magento -> Custom Payment Module for Moip (Brazil)
 * @category   Payment Gateway
 * @package    O2TI_Moip
 * @author     MoIP Pagamentos S/a
 * @copyright  Copyright (c) 2010 MoIP Pagamentos S/A
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
$installer = $this;
$installer->startSetup();

$directory_country_region = $installer->getTable('directory/country_region');
$directory_country_region_name = Mage::getSingleton('core/resource')->getTableName('directory_country_region_name');

$statusTable        = $installer->getTable('sales/order_status');
$statusStateTable   = $installer->getTable('sales/order_status_state');
$statusLabelTable   = $installer->getTable('sales/order_status_label');

$statuses = array(
	array('status' => 'authorized', 'label' => 'Autorizado'),
	array('status' => 'iniciado', 'label' => 'Iniciado'),
	array('status' => 'boleto_impresso', 'label' => 'Boleto Impresso'),
	array('status' => 'concluido', 'label' => 'Concluido')
);
$states = array(
	array('status' => 'authorized', 'state' => 'processing', 'is_default' => 1),
	array('status' => 'boleto_impresso', 'state' => 'holded', 'is_default' => 1),
	array('status' => 'iniciado', 'state' => 'processing', 'is_default' => 1),
	array('status' => 'concluido', 'state' => 'processing', 'is_default' => 1)
);

$estados = array(
	array("country_id"=>"BR", "code"=>"AC", "default_name"=>"Acre"),
	array("country_id"=>"BR", "code"=>"AL", "default_name"=>"Alagoas"),
	array("country_id"=>"BR", "code"=>"AP", "default_name"=>"Amazonas"),
	array("country_id"=>"BR", "code"=>"BA", "default_name"=>"Bahia"),
	array("country_id"=>"BR", "code"=>"CE", "default_name"=>"Ceará"),
	array("country_id"=>"BR", "code"=>"DF", "default_name"=>"Distrito Federal"),
	array("country_id"=>"BR", "code"=>"ES", "default_name"=>"Espírito Santo"),
	array("country_id"=>"BR", "code"=>"GO", "default_name"=>"Goiás"),
	array("country_id"=>"BR", "code"=>"MA", "default_name"=>"Maranhão"),
	array("country_id"=>"BR", "code"=>"MG", "default_name"=>"Minas Gerais"),
	array("country_id"=>"BR", "code"=>"MS", "default_name"=>"Mato Grosso do Sul"),
	array("country_id"=>"BR", "code"=>"MT", "default_name"=>"Mato Grosso"),
	array("country_id"=>"BR", "code"=>"PB", "default_name"=>"Paraíba"),
	array("country_id"=>"BR", "code"=>"PR", "default_name"=>"Paraná"),
	array("country_id"=>"BR", "code"=>"PE", "default_name"=>"Pernambuco"),
	array("country_id"=>"BR", "code"=>"PI", "default_name"=>"Piauí"),
	array("country_id"=>"BR", "code"=>"RJ", "default_name"=>"Rio de Janeiro"),
	array("country_id"=>"BR", "code"=>"RN", "default_name"=>"Rio Grande do Norte"),
	array("country_id"=>"BR", "code"=>"RS", "default_name"=>"Rio Grande do Sul"),
	array("country_id"=>"BR", "code"=>"RO", "default_name"=>"Rondônia"),
	array("country_id"=>"BR", "code"=>"RR", "default_name"=>"Roraima"),
	array("country_id"=>"BR", "code"=>"SC", "default_name"=>"Santa Catarina"),
	array("country_id"=>"BR", "code"=>"SP", "default_name"=>"São Paulo"),
	array("country_id"=>"BR", "code"=>"SE", "default_name"=>"Sergipe"),
	array("country_id"=>"BR", "code"=>"TO", "default_name"=>"Tocantins")
);
$installer->getConnection()->insertArray($statusTable, array('status', 'label'), $statuses);
$installer->getConnection()->insertArray($statusStateTable, array('status', 'state', 'is_default'), $states);
$installer->getConnection()->insertArray($directory_country_region, array('country_id', 'code', 'default_name'), $estados);

$installer->run("
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Acre'), ('pt_BR',LAST_INSERT_ID(),'Acre');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Alagoas'), ('pt_BR',LAST_INSERT_ID(),'Alagoas');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Amapá'), ('pt_BR',LAST_INSERT_ID(),'Amapá');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Amazonas'), ('pt_BR',LAST_INSERT_ID(),'Amazonas');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Bahia'), ('pt_BR',LAST_INSERT_ID(),'Bahia');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Ceará'), ('pt_BR',LAST_INSERT_ID(),'Ceará');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Distrito Federal'), ('pt_BR',LAST_INSERT_ID(),'Distrito Federal');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Espírito Santo'), ('pt_BR',LAST_INSERT_ID(),'Espírito Santo');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Goiás'), ('pt_BR',LAST_INSERT_ID(),'Goiás');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Maranhão'), ('pt_BR',LAST_INSERT_ID(),'Maranhão');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Mato Grosso'), ('pt_BR',LAST_INSERT_ID(),'Mato Grosso');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Mato Grosso do Sul'), ('pt_BR',LAST_INSERT_ID(),'Mato Grosso do Sul');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Minas Gerais'), ('pt_BR',LAST_INSERT_ID(),'Minas Gerais');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Pará'), ('pt_BR',LAST_INSERT_ID(),'Pará');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Paraíba'), ('pt_BR',LAST_INSERT_ID(),'Paraíba');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Paraná'), ('pt_BR',LAST_INSERT_ID(),'Paraná');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Pernambuco'), ('pt_BR',LAST_INSERT_ID(),'Pernambuco');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Piauí'), ('pt_BR',LAST_INSERT_ID(),'Piauí');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Rio de Janeiro'), ('pt_BR',LAST_INSERT_ID(),'Rio de Janeiro');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Rio Grande do Norte'), ('pt_BR',LAST_INSERT_ID(),'Rio Grande do Norte');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Rio Grande do Sul'), ('pt_BR',LAST_INSERT_ID(),'Rio Grande do Sul');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Rondônia'), ('pt_BR',LAST_INSERT_ID(),'Rondônia');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Roraima'), ('pt_BR',LAST_INSERT_ID(),'Roraima');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Santa Catarina'), ('pt_BR',LAST_INSERT_ID(),'Santa Catarina');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'São Paulo'), ('pt_BR',LAST_INSERT_ID(),'São Paulo');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Sergipe'), ('pt_BR',LAST_INSERT_ID(),'Sergipe');
INSERT INTO `".$directory_country_region_name."` (`locale`,`region_id`,`name`) VALUES('en_US',LAST_INSERT_ID(),'Tocantins'), ('pt_BR',LAST_INSERT_ID(),'Tocantins');");

$installer->endSetup();