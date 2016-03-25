/*
 Navicat Premium Data Transfer

 Source Server         : LOCALHOST MAMP
 Source Server Type    : MySQL
 Source Server Version : 50538
 Source Host           : localhost
 Source Database       : emprestimo

 Target Server Type    : MySQL
 Target Server Version : 50538
 File Encoding         : utf-8

 Date: 05/02/2015 03:56:09 AM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `emprestimo`
-- ----------------------------
DROP TABLE IF EXISTS `emprestimo`;
CREATE TABLE `emprestimo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cliente` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `dia` date DEFAULT NULL,
  `celular` varchar(30) DEFAULT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `valor` decimal(9,2) DEFAULT NULL,
  `taxaJuros` decimal(9,2) DEFAULT NULL,
  `pago` tinyint(1) DEFAULT NULL,
  `diaPago` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `emprestimoPagamento`
-- ----------------------------
DROP TABLE IF EXISTS `emprestimoPagamento`;
CREATE TABLE `emprestimoPagamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `data` date DEFAULT NULL,
  `valor` decimal(9,2) DEFAULT NULL,
  `emprestimo_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `emprestimo_id` (`emprestimo_id`),
  CONSTRAINT `emprestimopagamento_ibfk_1` FOREIGN KEY (`emprestimo_id`) REFERENCES `emprestimo` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

SET FOREIGN_KEY_CHECKS = 1;
