-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 07-Dez-2017 às 17:27
-- Versão do servidor: 5.7.14
-- PHP Version: 5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pawsapp`
--
CREATE DATABASE IF NOT EXISTS `pawsapp` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `pawsapp`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `mensagens`
--

CREATE TABLE `mensagens` (
  `id` int(11) NOT NULL,
  `id_de` int(11) NOT NULL,
  `id_para` int(11) NOT NULL,
  `mensagem` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `lido` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `mensagens`
--

INSERT INTO `mensagens` (`id`, `id_de`, `id_para`, `mensagem`, `time`, `lido`) VALUES
(1, 42, 20, 'oi', 1510934605, 0),
(2, 42, 20, 'turubom', 1510934644, 0),
(3, 14, 42, 'turubom', 1510934903, 0),
(4, 14, 15, 'Oi lua, tudo de bom?', 1510935519, 0),
(5, 42, 20, 'oi', 1510935807, 0),
(6, 14, 42, 'oi', 1510936020, 0),
(7, 14, 42, 'oioioio', 1510936127, 0),
(8, 14, 42, 'oi gta', 1510936301, 0),
(9, 14, 42, 'oi tete', 1510936424, 0),
(10, 42, 14, 'oi', 1510936566, 0),
(11, 14, 42, 'caralho abriu', 1510936973, 0),
(12, 14, 42, 'oiiii', 1510937287, 0),
(13, 14, 42, 'oi', 1510937352, 0),
(14, 14, 42, 'oi', 1510937356, 0),
(15, 42, 14, 'oi]', 1510937848, 0),
(16, 14, 42, 'oi', 1510937859, 0),
(17, 14, 42, 'ioioioioioio', 1510937898, 0),
(18, 14, 15, 'Tudo sim', 1510938335, 0),
(19, 14, 42, 'jj', 1510938476, 0),
(20, 47, 41, 'oi', 1511608574, 0),
(21, 47, 20, 'oi', 1511608702, 0),
(22, 47, 37, 'rfrtttttttt', 1511608802, 0),
(23, 47, 37, 't', 1511608803, 0),
(24, 47, 37, 't', 1511608803, 0),
(25, 47, 37, 't', 1511608803, 0),
(26, 47, 37, 't', 1511608804, 0),
(27, 47, 37, 't', 1511608804, 0),
(28, 47, 37, 't', 1511608804, 0),
(29, 47, 37, 'tt', 1511608804, 0),
(30, 47, 37, 't', 1511608805, 0),
(31, 47, 37, 'tt', 1511608805, 0),
(32, 47, 37, 't', 1511608805, 0),
(33, 47, 37, 'tt', 1511608806, 0),
(34, 47, 37, 't', 1511608806, 0),
(35, 47, 37, 't', 1511608806, 0),
(36, 47, 37, 't', 1511608807, 0),
(37, 47, 37, 't', 1511608807, 0),
(38, 47, 37, 't', 1511608808, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_bloqueio`
--

CREATE TABLE `tb_bloqueio` (
  `codigo` int(11) NOT NULL,
  `email` varchar(60) NOT NULL,
  `dataFinal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_bloqueio_usuario`
--

CREATE TABLE `tb_bloqueio_usuario` (
  `codigo` int(11) NOT NULL,
  `codUsuario` int(11) DEFAULT NULL,
  `codBloqueado` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_bloqueio_usuario`
--

INSERT INTO `tb_bloqueio_usuario` (`codigo`, `codUsuario`, `codBloqueado`) VALUES
(49, 39, 18);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_comentarios`
--

CREATE TABLE `tb_comentarios` (
  `codigo` int(11) NOT NULL,
  `codUsuario` int(11) NOT NULL,
  `codPostagem` int(11) NOT NULL,
  `conteudo` varchar(1200) NOT NULL,
  `dataHora` datetime NOT NULL,
  `ativo` varchar(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_comentarios`
--

INSERT INTO `tb_comentarios` (`codigo`, `codUsuario`, `codPostagem`, `conteudo`, `dataHora`, `ativo`) VALUES
(184, 21, 18, '<3', '2017-09-17 17:52:04', 'true'),
(185, 19, 15, 'melhor gato', '2017-09-17 17:58:05', 'true'),
(182, 18, 16, 'eu quero!!!', '2017-09-17 17:51:05', 'true'),
(238, 18, 49, 'oi', '2017-10-13 10:45:37', 'true'),
(180, 14, 18, 'aaaaaaaaaaaaaaaa <3', '2017-09-17 17:49:36', 'true'),
(179, 14, 16, 'oh meu deus', '2017-09-17 17:49:24', 'true'),
(191, 18, 17, 'odranreB', '2017-09-29 21:32:51', 'true'),
(210, 18, 22, 'asdas', '2017-09-30 18:08:04', 'true'),
(211, 18, 22, 'asdasd', '2017-09-30 18:08:05', 'true'),
(212, 18, 22, 'asdsad', '2017-09-30 18:08:07', 'true'),
(213, 18, 22, 'asdsad', '2017-09-30 18:08:09', 'true'),
(214, 18, 22, 'sadsad', '2017-09-30 18:08:10', 'true'),
(232, 18, 23, 'oi', '2017-10-10 12:25:40', 'true'),
(234, 17, 23, 'iae mano', '2017-10-10 12:36:07', 'true'),
(235, 16, 23, 'oi', '2017-10-10 12:36:34', 'true'),
(236, 17, 23, 'aaaaaa', '2017-10-10 12:46:09', 'true'),
(237, 38, 49, 'oi', '2017-10-10 14:27:59', 'false'),
(239, 39, 65, 'a', '2017-11-16 23:06:24', '1'),
(240, 17, 70, 'Lindo', '2017-11-17 00:58:07', 'true'),
(241, 41, 74, 'Falsa', '2017-11-17 01:07:18', 'true'),
(242, 17, 72, 'Ridicula', '2017-11-17 01:07:28', 'true'),
(243, 41, 67, 'Lindooo', '2017-11-17 01:10:25', 'true'),
(244, 18, 87, 'babaca horrivel', '2017-12-07 14:57:55', 'false'),
(245, 41, 87, 'Idiota', '2017-12-07 14:58:01', 'true'),
(246, 39, 87, 'Retardado', '2017-12-07 14:58:09', 'true');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_denuncias`
--

CREATE TABLE `tb_denuncias` (
  `codigo` int(11) NOT NULL,
  `codDenunciador` int(11) NOT NULL,
  `codigoDenunciado` int(11) NOT NULL,
  `tipoDenunciado` varchar(50) NOT NULL,
  `motivo` varchar(9) DEFAULT NULL,
  `codElemento` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_denuncias`
--

INSERT INTO `tb_denuncias` (`codigo`, `codDenunciador`, `codigoDenunciado`, `tipoDenunciado`, `motivo`, `codElemento`) VALUES
(56, 47, 43, 'postagem', 'irritante', 98),
(57, 47, 44, 'postagem', 'irritante', 97),
(58, 47, 45, 'postagem', 'irritante', 91),
(66, 47, 18, 'comentario', 'irritante', 244),
(60, 47, 41, 'comentario', 'irritante', 245),
(61, 47, 39, 'comentario', 'irritante', 246),
(62, 20, 14, 'perfil', 'Irritante', 0),
(63, 18, 19, 'perfil', 'irritante', 0),
(65, 22, 38, 'perfil', 'irritante', 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_favoritos`
--

CREATE TABLE `tb_favoritos` (
  `codigo` int(11) NOT NULL,
  `codPost` int(11) NOT NULL,
  `codUsuario` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_favoritos`
--

INSERT INTO `tb_favoritos` (`codigo`, `codPost`, `codUsuario`) VALUES
(64, 22, 18),
(9, 18, 18),
(11, 16, 18),
(12, 19, 18),
(13, 16, 15),
(14, 22, 15),
(15, 21, 15),
(16, 15, 15),
(17, 19, 15),
(18, 17, 15),
(80, 23, 18),
(65, 17, 18),
(66, 32, 18),
(68, 43, 18),
(69, 44, 18),
(73, 22, 17),
(81, 23, 17),
(82, 23, 16),
(83, 23, 20),
(84, 49, 18),
(85, 49, 19),
(86, 49, 38),
(87, 65, 39),
(88, 70, 17),
(89, 74, 41),
(90, 67, 41),
(91, 78, 41),
(92, 69, 42),
(93, 94, 42);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_notificacoes`
--

CREATE TABLE `tb_notificacoes` (
  `codigo` int(11) NOT NULL,
  `codRecebedor` int(11) NOT NULL,
  `codRemetente` int(11) NOT NULL,
  `codPost` int(11) DEFAULT NULL,
  `tipo` varchar(50) NOT NULL,
  `descricao` varchar(1000) NOT NULL,
  `foiLido` tinyint(1) NOT NULL,
  `dataHora` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_notificacoes`
--

INSERT INTO `tb_notificacoes` (`codigo`, `codRecebedor`, `codRemetente`, `codPost`, `tipo`, `descricao`, `foiLido`, `dataHora`) VALUES
(104, 38, 1, NULL, 'bem vindo', 'João, bem vindo a PawsApp!', 0, '2017-10-10 14:20:22'),
(33, 18, 1, 0, 'bem vindo', 'Bem vindo a PawsApp ;)', 1, '2017-01-01 11:37:13'),
(49, 9, 2, 5, 'comentario', 'Mateus e mais 2 comentaram na sua postagem.', 0, '2017-09-10 14:38:34'),
(50, 7, 2, 3, 'comentario', 'Mateus comentou na sua postagem.', 0, '2017-09-10 14:42:10'),
(46, 8, 2, 4, 'comentario', 'Mateus comentou na sua postagem.', 0, '2017-09-05 22:48:58'),
(54, 20, 18, 16, 'comentario', 'Mateus e mais 1 comentaram na sua postagem.', 1, '2017-09-17 17:51:05'),
(84, 21, 18, 18, 'comentario', 'Mateus e mais 1 comentaram na sua postagem.', 0, '2017-09-30 18:19:00'),
(55, 15, 19, 15, 'comentario', 'Victor  e mais 1 comentaram na sua postagem.', 0, '2017-09-17 17:58:05'),
(95, 15, 17, 22, 'favorito', 'Gabriella favoritou sua publicação.', 0, '2017-10-10 13:04:54'),
(93, 18, 17, 23, 'comentario', 'Gabriella e mais 1 comentaram em sua publicação.', 1, '2017-10-10 12:46:09'),
(30, 2, 9, 14, 'comentario', 'Victor e mais 1 comentaram na sua postagem.', 1, '2017-08-27 11:18:47'),
(107, 18, 38, 49, 'comentario', 'Jo??o comentou em sua publicação.', 1, '2017-10-10 14:27:59'),
(106, 18, 38, 49, 'favorito', 'Jo??o e mais 1 favoritaram sua publicação.', 1, '2017-10-10 14:27:12'),
(103, 18, 20, 23, 'favorito', 'Lucas e mais 2 favoritaram sua publicação.', 1, '2017-10-10 13:27:38'),
(108, 39, 1, NULL, 'bem vindo', 'Mateus, bem vindo a PawsApp!', 0, '2017-11-16 23:02:13'),
(109, 40, 1, NULL, 'bem vindo', 'Gabrielle, bem vindo a PawsApp!', 0, '2017-11-17 00:44:18'),
(110, 40, 17, 70, 'favorito', 'Gabriella favoritou sua publicação.', 0, '2017-11-17 00:58:02'),
(111, 40, 17, 70, 'comentario', 'Gabriella comentou em sua publicação.', 0, '2017-11-17 00:58:07'),
(112, 41, 1, NULL, 'bem vindo', 'Victor, bem vindo a PawsApp!', 0, '2017-11-17 01:06:56'),
(113, 17, 41, 74, 'favorito', 'Victor favoritou sua publicação.', 0, '2017-11-17 01:07:14'),
(114, 17, 41, 74, 'comentario', 'Victor comentou em sua publicação.', 0, '2017-11-17 01:07:18'),
(115, 17, 41, 72, 'comentario', 'Victor comentou em sua publicação.', 0, '2017-11-17 01:07:28'),
(116, 40, 41, 67, 'favorito', 'Victor favoritou sua publicação.', 0, '2017-11-17 01:10:15'),
(117, 40, 41, 67, 'comentario', 'Victor comentou em sua publicação.', 0, '2017-11-17 01:10:25'),
(118, 42, 1, NULL, 'bem vindo', 'Thalita, bem vindo a PawsApp!', 0, '2017-11-17 01:14:07'),
(119, 43, 1, NULL, 'bem vindo', 'Ester, bem vindo a PawsApp!', 0, '2017-11-17 01:20:12'),
(120, 44, 1, NULL, 'bem vindo', 'Caroline, bem vindo a PawsApp!', 0, '2017-11-17 01:26:53'),
(121, 45, 1, NULL, 'bem vindo', 'Jéssica, bem vindo a PawsApp!', 0, '2017-11-17 01:32:57'),
(122, 40, 42, 69, 'favorito', 'Thalita favoritou sua publicação.', 0, '2017-11-17 13:11:52'),
(123, 45, 42, 94, 'favorito', 'Thalita favoritou sua publicação.', 0, '2017-11-17 13:20:28'),
(124, 46, 1, NULL, 'bem vindo', 'asdsa, bem vindo a PawsApp!', 0, '2017-11-25 09:11:59'),
(125, 47, 1, NULL, 'bem vindo', 'Mateus, bem vindo a PawsApp!', 1, '2017-11-25 09:14:34'),
(126, 44, 47, 87, 'comentario', 'Mateus comentou em sua publicação.', 0, '2017-12-07 14:57:55'),
(127, 44, 47, 87, 'comentario', 'Mateus comentou em sua publicação.', 0, '2017-12-07 14:58:01'),
(128, 44, 47, 87, 'comentario', 'Mateus comentou em sua publicação.', 0, '2017-12-07 14:58:09'),
(129, 44, 47, 87, 'comentario', 'Mateus comentou em sua publicação.', 0, '2017-12-07 14:58:14');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_palavrasdebaixocalao`
--

CREATE TABLE `tb_palavrasdebaixocalao` (
  `codigo` int(11) NOT NULL,
  `conteudoObliquo` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_palavrasdebaixocalao`
--

INSERT INTO `tb_palavrasdebaixocalao` (`codigo`, `conteudoObliquo`) VALUES
(1, 'caralho'),
(2, 'porra'),
(16, 'filho da puta'),
(15, 'filha da puta'),
(14, 'vai se foder'),
(13, 'vai tomar no cu'),
(12, 'foda'),
(17, 'vadia');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_posts`
--

CREATE TABLE `tb_posts` (
  `codigo` int(11) NOT NULL,
  `codUsuario` int(11) NOT NULL,
  `tipoPost` varchar(50) NOT NULL,
  `imgPost` varchar(60) NOT NULL,
  `dataPostagem` datetime NOT NULL,
  `descricaoPost` varchar(300) NOT NULL,
  `nomeAnimal` varchar(50) NOT NULL,
  `especieAnimal` varchar(30) NOT NULL,
  `porteAnimal` varchar(20) NOT NULL,
  `sexoAnimal` varchar(20) NOT NULL,
  `cor` varchar(50) NOT NULL,
  `raca` varchar(100) DEFAULT NULL,
  `contato` varchar(20) DEFAULT NULL,
  `localidade` varchar(100) DEFAULT NULL,
  `ativo` varchar(6) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_posts`
--

INSERT INTO `tb_posts` (`codigo`, `codUsuario`, `tipoPost`, `imgPost`, `dataPostagem`, `descricaoPost`, `nomeAnimal`, `especieAnimal`, `porteAnimal`, `sexoAnimal`, `cor`, `raca`, `contato`, `localidade`, `ativo`) VALUES
(16, 20, 'doacao', '_media_posts/a.jpg', '2017-09-25 00:00:00', 'Doa-se gata bonitinha.', 'Sophia', 'gato', 'pequeno', 'F', 'preto', 'Persa', '(11) 98765-9090', 'Rua Parcoal Dantas', 'true'),
(15, 15, 'casual', '_media_posts/b.jpg', '2017-09-22 13:20:05', 'meu gato é vesgo', 'Thor', 'Gato', 'pequeno', 'M', 'preto', NULL, NULL, NULL, 'true'),
(17, 17, 'perdido', '_media_posts/d.jpg', '2017-05-23 00:00:00', 'Atende pelo nome', 'Bernardo', 'cachorro', 'médio', 'M', 'preto e branco', NULL, NULL, NULL, 'true'),
(18, 21, 'casual', '_media_posts/09583a5a54a380dbcb79512c1e828bc5.jpg', '2017-05-23 22:30:00', 'catioro sorrindo', 'Maike', 'cachorro', 'grande', 'M', 'bege', NULL, NULL, NULL, 'true'),
(19, 19, 'perdido', '_media_posts/banana.gif', '2017-06-12 00:00:00', '', 'Mia', 'gato', 'pequeno', 'F', 'preto e branco', NULL, NULL, NULL, 'true'),
(49, 18, 'perdido', '_media_posts/6d50e225d31b407c15f1bb13cba2d0f6.jpg', '2017-10-10 13:57:21', 'Esse é o Spike, Spike está perdido portanto sem pão. Ajude-nos. Por favor pessoal.', 'Spike', 'cachorro', 'pequeno', 'Ambos', 'marrom', 'outra', '(11) 11111-1111', 'Brasilzão não é pra mim!', 'false'),
(22, 15, 'casual', '_media_posts/9b952040d79fdf2b1d72443525dee1cf.jpg', '2017-09-26 20:49:00', 'A Lua é minha gata!', 'Lua', '', '', '', '', '', '', '', 'true'),
(23, 18, 'doacao', '_media_posts/09583a5a54a380dbcb79512c1e828bc5.jpg', '2017-09-29 20:47:11', 'Olá, sou dono do Rhaegar, ele é um rottweiler puro, estou precisando mudar de cidade e lá o espaço será muito pequeno. Pensei em doar quem tiver interesse chama index.', 'Rhaegar', 'cachorro', 'grande', 'macho', 'preto', 'rottweiler', '(11) 11111-1111', 'Rua Marasca, Bairro Jardim Camargo Novo.', 'true'),
(65, 39, 'casual', '_media_posts/63617c7f8e34713143925ac56ced3d66.jpg', '2017-11-16 23:05:38', 'dsdsa', 'Larissa', '', '', '', '', '', '', '', 'true'),
(66, 40, 'casual', '_media_posts/03d3f494c4f552440723135adb685910.jpg', '2017-11-17 00:48:12', 'Eu amo o Tedy!', 'Tedy', '', '', '', '', '', '', '', 'true'),
(67, 40, 'doacao', '_media_posts/1b82d22e95315f7f90029d612da98191.jpg', '2017-11-17 00:50:47', 'Olá, sou dona da Lily, estou precisando mudar de cidade e lá o espaço será muito pequeno. Pensei em doar quem tiver interesse chama index.', 'Lily', 'cachorro', 'medio', 'Fêmea', 'preto', 'pastoralemao', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(68, 40, 'perdido', '_media_posts/d3897c2c4774ad967a9fc0245fd2d6ee.jpg', '2017-11-17 00:53:43', 'Atende pelo nome', 'Pow', 'gato', 'pequeno', 'Macho', 'branco', 'siames', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(69, 40, 'encontrado', '_media_posts/049c8206aac074d39239a3455df72615.jpg', '2017-11-17 00:55:43', 'Encontrei esse bichinho próximo a minha casa, se você é o dono ou saiba quem é, por favor entre em contato.', 'Encontre meu dono', 'passaro', 'pequeno', 'Fêmea', 'branco', 'canarios', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(70, 40, 'encontrado', '_media_posts/e49f6f07da4f2ec34413c6a8796ec8f6.jpg', '2017-11-17 00:56:52', 'Encontrei esse bichinho próximo a minha casa, se você é o dono ou saiba quem é, por favor entre em contato.', 'Encontre meu dono', 'gato', 'pequeno', 'Macho', 'branco', 'persa', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(71, 17, 'casual', '_media_posts/7ae0011abfb1f81a7d28776656e5bbe4.jpg', '2017-11-17 00:59:10', 'O gatinho mais engraçado que você vai ver hoje rs', 'Darth Vader?', '', '', '', '', '', '', '', 'true'),
(72, 17, 'doacao', '_media_posts/e86a2f746a4c40799f3f4caee9d1017a.jpg', '2017-11-17 01:01:02', 'Olá, sou dona do Nik, estou precisando mudar de cidade e lá o espaço será muito pequeno. Pensei em doar quem tiver interesse chama index.', 'Nik', 'gato', 'pequeno', 'Macho', 'preto', '', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(73, 17, 'perdido', '_media_posts/fe07a4ce9c746c12c0d5d0e8206dc41f.jpg', '2017-11-17 01:02:57', 'Atende pelo nome. Caso tenha informações entre em contato urgentemente.', 'Mily', 'cachorro', 'medio', 'Fêmea', 'naoInformado', '', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(74, 17, 'encontrado', '_media_posts/7f07c1e166ddadaa0ddae13d16793c4e.jpg', '2017-11-17 01:05:05', 'Encontrei esse bichinho próximo a minha casa, se você é o dono ou saiba quem é, por favor entre em contato.', 'Encontre meu dono', 'outros', 'pequeno', 'Macho', 'naoInformado', '', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(75, 41, 'casual', '_media_posts/bd3522224bea32b7d160b24e6a2e3320.jpg', '2017-11-17 01:08:19', 'Essa gata é mais bonita que minha crush.', 'Maggie', '', '', '', '', '', '', '', 'true'),
(76, 41, 'doacao', '_media_posts/4a748a9dd409812fa5151b9b84723a0e.jpg', '2017-11-17 01:10:03', 'Olá, sou dono das tartarugas,,estou precisando mudar de cidade e lá o espaço será muito pequeno. Pensei em doar quem tiver interesse chama index.', 'Encontre um lar', 'outros', 'pequeno', 'Ambos', 'naoInformado', '', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(77, 41, 'perdido', '_media_posts/b3bdf67f6555794b9246b7319943f247.jpg', '2017-11-17 01:11:29', 'Atende pelo nome. Caso tenha informações entre em contato urgentemente.', 'Jully', 'cachorro', 'medio', 'Fêmea', 'marrom', 'naodefinido', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(78, 41, 'encontrado', '_media_posts/3c4df5e163373e483f7cdaf384a5e78f.jpg', '2017-11-17 01:12:51', 'Encontrei esse bichinho próximo a minha casa, se você é o dono ou saiba quem é, por favor entre em contato.', 'Encontre meu dono', 'cachorro', 'pequeno', 'Macho', 'marrom', 'outra', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(79, 42, 'casual', '_media_posts/f357b57703a3d5a12d0c9eaeb8b0ede1.jpg', '2017-11-17 01:14:59', 'Preparadíssima para o casamento', 'A noiva mais linda', '', '', '', '', '', '', '', 'true'),
(80, 42, 'doacao', '_media_posts/1d12b2b0041b294d89995e8da07cd363.jpg', '2017-11-17 01:16:33', 'Olá, sou dona do pássaro,estou precisando mudar de cidade e lá o espaço será muito pequeno. Pensei em doar quem tiver interesse chama index.', 'Encontre um lar', 'passaro', 'pequeno', 'Macho', 'naoInformado', 'canarios', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(81, 42, 'perdido', '_media_posts/44ab17c02cf00cfdcd6ff6063ba1c8d9.jpg', '2017-11-17 01:17:52', 'Atende pelo nome. Caso tenha informações entre em contato urgentemente.', 'Moon', 'gato', 'pequeno', 'Fêmea', 'marrom', 'persa', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(82, 42, 'encontrado', '_media_posts/602f2c13ac4e0a784b7cba8375ba78dd.jpg', '2017-11-17 01:19:00', 'Encontrei esse bichinho próximo a minha casa, se você é o dono ou saiba quem é, por favor entre em contato.', 'Encontre meu dono', 'cachorro', 'grande', 'Macho', 'preto', 'doberman', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(83, 43, 'casual', '_media_posts/f311aab016e7c140c85583cb9656d640.jpg', '2017-11-17 01:21:01', 'Indo passear, com a roupa nova <3', 'Lari', '', '', '', '', '', '', '', 'true'),
(84, 43, 'doacao', '_media_posts/832ab8b1db3062d9c30340fcca56fd40.jpg', '2017-11-17 01:23:04', 'Olá, sou dona do Bob, estou precisando mudar de cidade e lá o espaço será muito pequeno. Pensei em doar, quem tiver interesse chama index.', 'Bob', 'outros', 'pequeno', 'Macho', 'naoInformado', '', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(85, 43, 'perdido', '_media_posts/2158df1f552580a11e1e1efefa4fd3a2.jpg', '2017-11-17 01:24:07', 'Atende pelo nome. Caso tenha informações entre em contato urgentemente.', 'Roedor', 'outros', 'pequeno', 'Fêmea', 'naoInformado', '', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(86, 43, 'encontrado', '_media_posts/75de65c3fc1750174c02d06f887b099b.jpg', '2017-11-17 01:25:50', 'Encontrei esse bichinho próximo a minha casa, se você é o dono ou saiba quem é, por favor entre em contato.', 'Encontre meu dono', 'cachorro', 'grande', 'Macho', 'listrado', 'naodefinido', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(87, 44, 'casual', '_media_posts/d03d922ac8c8029c1ee6c83d2cdac35c.jpg', '2017-11-17 01:27:55', 'Toda cheia de mimimi <3', 'Aila', '', '', '', '', '', '', '', 'true'),
(88, 44, 'doacao', '_media_posts/7ee8e47b7063a008e388ee2cdd79c6ca.jpg', '2017-11-17 01:29:22', 'Olá, sou dona do Rhaegar ,estou precisando mudar de cidade e lá o espaço será muito pequeno. Pensei em doar quem tiver interesse chama index.', 'Luna', 'cachorro', 'grande', 'Fêmea', 'naoInformado', 'outra', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(89, 44, 'perdido', '_media_posts/4f8012edd4498d195cf65972aa4e07f0.jpg', '2017-11-17 01:31:05', 'Atende pelo nome. Caso tenha informações entre em contato urgentemente.', 'Mike', 'cachorro', 'medio', 'Macho', 'naoInformado', 'outra', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(90, 44, 'encontrado', '_media_posts/15e07773583c1cfdf8f6ddee5d909c48.jpg', '2017-11-17 01:32:05', 'Encontrei esse bichinho próximo a minha casa, se você é o dono ou saiba quem é, por favor entre em contato.', 'Encontre meu dono', 'outros', 'pequeno', 'Macho', 'naoInformado', '', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(91, 45, 'casual', '_media_posts/b7850d1a0fade2db1d63b3e3202a9d0e.jpg', '2017-11-17 01:34:32', 'Esbanjando sensualidade', 'Gil', '', '', '', '', '', '', '', 'true'),
(92, 45, 'doacao', '_media_posts/e4bdfe049625e5a40ba40fb0f0ada752.jpg', '2017-11-17 01:36:38', 'Olá, sou dona da Mex, ,estou precisando mudar de cidade e lá o espaço será muito pequeno. Pensei em doar quem tiver interesse chama index.', 'Papagaio', 'outros', 'pequeno', 'Fêmea', 'naoInformado', '', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(93, 45, 'perdido', '_media_posts/3bcc48ebcfacc4352edcbe5ecd6bdd78.jpg', '2017-11-17 01:38:23', 'Atende pelo nome. Caso tenha informações entre em contato urgentemente.', 'Milk', 'passaro', 'pequeno', 'Macho', 'naoInformado', 'canarios', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(94, 45, 'encontrado', '_media_posts/592da6a6dd85646893fb41fef84899d7.jpg', '2017-11-17 01:39:27', 'Encontrei esse bichinho próximo a minha casa, se você é o dono ou saiba quem é, por favor entre em contato.', 'Encontre meu dono', 'cachorro', 'medio', 'Fêmea', 'naoInformado', 'outra', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(95, 17, 'doacao', '_media_posts/252c8faf634430dd074d2be0c0ca457b.jpg', '2017-11-17 01:41:58', 'Olá, sou dona da gatinha, ,estou precisando mudar de cidade e lá o espaço será muito pequeno. Pensei em doar quem tiver interesse chama index.', 'Me adota', 'gato', 'pequeno', 'Fêmea', 'branco', '', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(96, 41, 'encontrado', '_media_posts/85dc3d49dd9a7437d3ba03275bf0da3e.jpg', '2017-11-17 01:43:53', 'Encontrei esses bichinhos próximo a minha casa, se você é o dono ou saiba quem é, por favor entre em contato.', 'Um casal de coelhos sem seus donos', 'outros', 'pequeno', 'Ambos', 'naoInformado', '', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(97, 44, 'perdido', '_media_posts/d917a98234dc3cf6a6ccfca883d7d5f3.jpg', '2017-11-17 01:46:18', 'Caso tenha informações entre em contato urgentemente. Ela é agressiva.', 'Laika', 'cachorro', 'pequeno', 'Fêmea', 'naoInformado', 'outra', '(11) 11111-1111', 'Jardim das Flores, 123', 'true'),
(98, 43, 'casual', '_media_posts/1ffac0bf7038b756cf247ca2e94fc470.jpg', '2017-11-17 01:49:30', 'A maior tartaruga que você respeita.', 'Laila', '', '', '', '', '', '', '', 'true');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_seguidor_seguido`
--

CREATE TABLE `tb_seguidor_seguido` (
  `codigo` int(11) NOT NULL,
  `codSeguidor` int(11) NOT NULL,
  `codSeguido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_seguidor_seguido`
--

INSERT INTO `tb_seguidor_seguido` (`codigo`, `codSeguidor`, `codSeguido`) VALUES
(2, 14, 21),
(3, 14, 20),
(4, 20, 21),
(5, 21, 20),
(6, 39, 18);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_tentativas`
--

CREATE TABLE `tb_tentativas` (
  `codigo` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `horario` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_tentativas`
--

INSERT INTO `tb_tentativas` (`codigo`, `email`, `horario`) VALUES
(1, 'magnata1997@gmail.com', '2017-08-06 10:42:49'),
(2, 'magnata1997@gmail.com', '2017-08-06 10:42:55'),
(3, 'magnata1997@gmail.com', '2017-08-06 10:43:07'),
(4, 'magnata1997@gmail.com', '2017-08-06 11:09:53'),
(5, 'magnata1997@gmail.com', '2017-08-06 16:45:59'),
(7, 'magnata1997@gmail.com', '2017-08-08 21:19:38'),
(17, 'deborahdiasokasaki@gmail.com', '2017-09-17 12:09:01'),
(18, 'deborahdiasokasaki@gmail.com', '2017-09-19 16:18:56'),
(19, 'mateus@mateus.com', '2017-09-26 20:11:06'),
(20, 'deborahdiasokasaki@gmail.com', '2017-09-26 20:35:44'),
(21, 'victor.martinsmelo@gmail.com', '2017-09-26 20:37:38'),
(22, 'lualinybritopeixoto@gmail.com', '2017-09-26 20:44:12'),
(23, 'magnata1997@gmail.com', '2017-09-30 17:46:33'),
(24, 'magnata1997@gmail.com', '2017-10-01 16:40:03'),
(25, 'mateus@mateus.com', '2017-10-10 11:49:22'),
(26, 'mateus@mateus.com', '2017-10-10 11:49:29'),
(27, 'gabbie.almeida.oliver@gmail.com', '2017-10-10 12:24:28'),
(28, 'gabbie.almeida.oliver@gmail.com', '2017-10-10 12:24:32'),
(29, 'gabbie.almeida.oliver@gmail.com', '2017-10-10 12:24:41'),
(30, 'gabbie.almeida.oliver@gmail.com', '2017-10-10 13:04:18'),
(31, 'gabbie.almeida.oliver@gmail.com', '2017-10-10 13:04:25'),
(32, 'magnata1997@gmail.com', '2017-10-12 17:41:07'),
(33, 'pawsapp_1997@gmail.com', '2017-11-16 14:26:40'),
(34, 'pawsapp_1997@gmail.com', '2017-11-16 14:26:55'),
(35, 'magnata1997@gmail.com', '2017-11-16 14:27:09'),
(36, 'magnata1997@gmail.com', '2017-11-16 14:27:10'),
(37, 'pawsapp_1997@gmail.com', '2017-11-16 14:27:39'),
(38, 'pawsapp_1997@gmail.com', '2017-11-16 14:27:54'),
(39, 'mateus@mateus.com', '2017-11-16 15:06:22'),
(40, 'mateus@mateus.com', '2017-11-16 15:06:26'),
(41, 'mateus@mateus.com', '2017-11-16 22:58:56'),
(42, 'mateus@mateus.com', '2017-11-16 22:59:03'),
(43, 'pawsapp2017@gmail.com', '2017-11-16 23:57:01'),
(44, 'mateus@mateus.com', '2017-11-17 00:09:23'),
(45, 'mateus@mateus.com', '2017-11-17 00:07:43'),
(46, 'gabbie@gabbie.com', '2017-11-17 01:40:23'),
(47, 'thalita@thalita.com', '2017-11-17 01:49:55'),
(48, 'thalita@thalita.com', '2017-11-17 01:50:00'),
(49, 'thalita@thalita.com', '2017-11-17 01:50:07'),
(50, 'thalita@thalita.com', '2017-11-17 01:50:13'),
(51, 'magnata@gmail.com', '2017-11-25 09:13:45'),
(52, 'magnata1997@gmail.com', '2017-12-07 14:56:44');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_usuarios`
--

CREATE TABLE `tb_usuarios` (
  `codigo` int(11) NOT NULL,
  `permissao` int(11) NOT NULL,
  `logado` int(11) NOT NULL,
  `statusAtivacao` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `sobrenome` varchar(50) NOT NULL,
  `dataNascimento` date NOT NULL,
  `sexo` varchar(1) DEFAULT NULL,
  `foto` varchar(48) NOT NULL,
  `telefoneFixo` varchar(15) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `email` varchar(70) NOT NULL,
  `senha` varchar(60) NOT NULL,
  `cidade` varchar(50) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `dataInscricao` date DEFAULT NULL,
  `ativo` varchar(6) NOT NULL,
  `horario` datetime DEFAULT NULL,
  `limite` datetime DEFAULT NULL,
  `blocks` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `tb_usuarios`
--

INSERT INTO `tb_usuarios` (`codigo`, `permissao`, `logado`, `statusAtivacao`, `nome`, `sobrenome`, `dataNascimento`, `sexo`, `foto`, `telefoneFixo`, `celular`, `email`, `senha`, `cidade`, `uf`, `dataInscricao`, `ativo`, `horario`, `limite`, `blocks`) VALUES
(20, 1, 0, 0, 'Lucas', 'Vilela', '2000-08-08', 'M', '_imgs_users/65fed30a25a9cac1d9077d784d4113e2.jpg', '11 20562681', '11 964156873', 'luckaleixo@yahoo.com.br', 'c7eef6c3e2b2f90dc10eaf5fe3e4cbc9', 'São Paulo', 'SP', NULL, 'true', NULL, NULL, ''),
(14, 1, 0, 0, 'Deborah', 'Dias Okasaki', '2000-10-17', 'F', '_imgs_users/5a0909611d23c5cc39865d4631ae9996.jpg', '11 20526603', '11 942897705', 'deborahdiasokasaki@gmail.com', 'e9e9d0b5f5ce8b4a683eaf877e420886', 'São Paulo', 'SP', NULL, 'true', '2017-11-17 16:36:37', '2017-11-17 17:06:52', ''),
(15, 1, 0, 0, 'Lua', 'Brito', '1998-12-29', 'F', '_imgs_users/2b152b3d0ed8e7db789ea14a423b3392.jpg', '11 25895010', '11 977057408', 'lualinybritopeixoto@gmail.com', 'd1bc816ebccc73adeac1a8fde26456fb', 'São Paulo', 'SP', NULL, 'true', NULL, NULL, ''),
(16, 1, 0, 0, 'Bruno', 'Vinicius', '1999-02-14', 'M', '_imgs_users/empty.png', '11 24846233', '11 986474675', 'brvno142@gmail.com', '1e035e1dde5eed1a01e19a65678793bb', 'São Paulo', 'SP', NULL, 'true', NULL, NULL, ''),
(17, 1, 0, 0, 'Gabriella', 'Almeida', '2000-09-22', 'F', '_imgs_users/1797378b99ff112e1b4352e4aac91eb7.jpg', '11 25113819', '11 958505521', 'gabbie.almeida.oliver@gmail.com', 'e9e5e912ca8cce175a278b01d77da4f8', 'São Paulo', 'SP', NULL, 'false', NULL, NULL, ''),
(18, 1, 0, 0, 'Mateus', 'Soares', '1997-09-18', 'M', '_imgs_users/c615c33354875a28cbb3d1a1e0c7804c.jpg', NULL, '11 981375667', 'magnata1997@gmail.com', 'ad17a78166831b5ef2a388e05a8c98c8', 'São Paulo', 'SP', NULL, 'true', NULL, NULL, ''),
(19, 1, 0, 0, 'Victor', 'Martins', '2000-12-04', 'M', '_imgs_users/4295dfdd4d9394ca6be3de0edc0a398d.jpg', NULL, '11 970755374', 'victor.martinsmelo@gmail.com', '4f3bc3f74b6a92cec8d3fb62c5001d4d', 'São Paulo', 'SP', NULL, 'false', NULL, NULL, ''),
(21, 1, 0, 0, 'Nathalia', 'Caroline', '1999-05-10', 'F', '_imgs_users/empty.png', '11 20514519', '11 949391252', 'nathaliacarolinedantas@yahoo.com', '1edbe2ea3cb8debe310be97f2ccb8da5', 'São Paulo', 'SP', NULL, 'true', NULL, NULL, ''),
(22, 1, 0, 0, 'Camila', 'Souza', '2000-09-26', 'F', '_imgs_users/empty.png', NULL, '11 977208925', 'camilinhasbrandao@gmail.com', 'e64e9ca256f650c1836c5c16f1df0aad', 'São Paulo', 'SP', NULL, 'true', NULL, NULL, ''),
(38, 1, 1, 1, 'João', 'Victor', '2000-12-20', 'M', '_imgs_users/empty.png', '', '', 'joao@joao.com', 'adbc91a43e988a3b5b745b8529a90b61', 'Seará', 'SE', '2017-10-10', 'false', NULL, NULL, ''),
(1, 2, 1, 1, 'PawsApp', 'Company', '2016-09-20', 'M', '_imgs_users/2be083d6b9bd2f37a8b4e729286b2d54.jpg', '', '', 'pawsapp2017@gmail.com', '32c262cfad0c910d33a19dac718ca013', 'São Paulo', 'SP', '2017-10-09', 'true', NULL, NULL, ''),
(37, 1, 1, 1, 'Mateus', 'Soares da Silva', '1997-11-18', 'M', '_imgs_users/empty.png', '', '', 'mateus@mateus.com', 'ad17a78166831b5ef2a388e05a8c98c8', 'São Paulo', 'SP', '2017-10-10', 'true', NULL, NULL, ''),
(39, 1, 1, 1, 'Mateus', 'Soares', '1997-11-18', 'M', '_imgs_users/empty.png', '', '', 'mateus1@mateus.com', 'ad17a78166831b5ef2a388e05a8c98c8', 'São Paulo', 'SP', '2017-11-16', 'true', NULL, NULL, ''),
(40, 1, 1, 1, 'Gabrielle', 'Merces', '2000-03-30', 'F', '_imgs_users/8f537b1c97891831a6f775f80196dc9c.jpg', '(11) 1111-1111', '(11) 11111-1111', 'gabi@gabi.com', 'e9e5e912ca8cce175a278b01d77da4f8', 'São Paulo', 'SP', '2017-11-17', 'true', NULL, NULL, ''),
(41, 1, 1, 1, 'Victor', 'Soares', '2000-06-12', 'M', '_imgs_users/646c3e17cbe27ac279ee49817edd847c.jpg', '(11) 1111-1111', '(11) 11111-1111', 'victors@victors.com', '4f3bc3f74b6a92cec8d3fb62c5001d4d', 'São Paulo', 'SP', '2017-11-17', 'true', NULL, NULL, ''),
(42, 1, 1, 1, 'Thalita', 'Calado', '1999-11-02', 'F', '_imgs_users/6a9c2af5519a4eae9ea94a88c544ae8b.jpg', '(11) 1111-1111', '(11) 11111-1111', 'thalita@thalita.com', '3e4c48ac51301894a74dbdd8b196c74c', 'São Paulo', 'SP', '2017-11-17', 'true', '2017-11-17 17:08:34', '2017-11-17 17:09:36', ''),
(43, 1, 1, 1, 'Ester', 'Gama', '2000-05-22', 'F', '_imgs_users/279bad2cfacaa7799cc288aebc0b3669.jpg', '(11) 1111-1111', '(11) 11111-1111', 'ester@ester.com', '6c91956fab01e8189ebc2d0577d460a4', 'Uma cidade', 'PE', '2017-11-17', 'true', NULL, NULL, ''),
(44, 1, 1, 1, 'Caroline', 'Pimentel', '2001-09-06', 'F', '_imgs_users/b9a9991c0868f413f5c25e3cb44d3478.jpg', '(11) 1111-1111', '(11) 11111-1111', 'carol@carol.com', '1acdb97ee0c31a73f0ac13b6789034ba', 'Uma cidade', 'PI', '2017-11-17', 'true', NULL, NULL, ''),
(45, 1, 1, 1, 'Jéssica', 'Silva', '2000-12-14', '', '_imgs_users/9d2c5c2af7ce28839a9f3ce6d762fd57.jpg', '(11) 1111-1111', '(11) 11111-1111', 'jessica@jessica.com', 'eb4f99636d3ba72c9df325e6c78f45f4', 'Uma cidade', 'PA', '2017-11-17', 'true', NULL, NULL, ''),
(46, 1, 1, 1, 'asdsa', 'sadsad', '1991-11-11', 'M', '_imgs_users/empty.png', '(11) 1111-1111', '(11) 11111-1111', 'magnata@gmail.com', '664fae06a748e656511d55b59fc6f85e', 'São Paulo', 'SP', '2017-11-25', 'true', '2017-11-25 12:12:58', '2017-11-25 12:14:58', ''),
(47, 1, 1, 1, 'Mateus', 'Soares', '1997-11-18', 'M', '_imgs_users/empty.png', '(11) 1111-1111', '(11) 11111-1111', 'magnata1@gmail.com', 'ede85d7c3f02a3cc4693fbd3b6700743', 'São Paulo', 'SP', '2017-11-25', 'true', '2017-12-07 17:00:07', '2017-12-07 17:02:07', '');

--
-- Acionadores `tb_usuarios`
--
DELIMITER $$
CREATE TRIGGER `AI_NOTIFICA_USUARIO` AFTER INSERT ON `tb_usuarios` FOR EACH ROW INSERT INTO `tb_notificacoes` VALUES (NULL,NEW.codigo,1,NULL,'bem vindo',CONCAT(NEW.nome,', bem vindo a PawsApp!'),0,NOW())
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mensagens`
--
ALTER TABLE `mensagens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_bloqueio`
--
ALTER TABLE `tb_bloqueio`
  ADD PRIMARY KEY (`codigo`);

--
-- Indexes for table `tb_bloqueio_usuario`
--
ALTER TABLE `tb_bloqueio_usuario`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `codUsuario` (`codUsuario`),
  ADD KEY `codBloqueado` (`codBloqueado`);

--
-- Indexes for table `tb_comentarios`
--
ALTER TABLE `tb_comentarios`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_codUsuario` (`codUsuario`),
  ADD KEY `fk_codPostagem` (`codPostagem`);

--
-- Indexes for table `tb_denuncias`
--
ALTER TABLE `tb_denuncias`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `codDenunciador` (`codDenunciador`);

--
-- Indexes for table `tb_favoritos`
--
ALTER TABLE `tb_favoritos`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `codPost` (`codPost`),
  ADD KEY `codUsuario` (`codUsuario`);

--
-- Indexes for table `tb_notificacoes`
--
ALTER TABLE `tb_notificacoes`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `codUsuario` (`codRecebedor`),
  ADD KEY `id_fk_dono_postagem` (`codRemetente`),
  ADD KEY `id_fk_post` (`codPost`);

--
-- Indexes for table `tb_palavrasdebaixocalao`
--
ALTER TABLE `tb_palavrasdebaixocalao`
  ADD PRIMARY KEY (`codigo`);

--
-- Indexes for table `tb_posts`
--
ALTER TABLE `tb_posts`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `fk_codUsuario` (`codUsuario`);

--
-- Indexes for table `tb_seguidor_seguido`
--
ALTER TABLE `tb_seguidor_seguido`
  ADD PRIMARY KEY (`codigo`);

--
-- Indexes for table `tb_tentativas`
--
ALTER TABLE `tb_tentativas`
  ADD PRIMARY KEY (`codigo`);

--
-- Indexes for table `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  ADD PRIMARY KEY (`codigo`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mensagens`
--
ALTER TABLE `mensagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `tb_bloqueio`
--
ALTER TABLE `tb_bloqueio`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_bloqueio_usuario`
--
ALTER TABLE `tb_bloqueio_usuario`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `tb_comentarios`
--
ALTER TABLE `tb_comentarios`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=248;
--
-- AUTO_INCREMENT for table `tb_denuncias`
--
ALTER TABLE `tb_denuncias`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;
--
-- AUTO_INCREMENT for table `tb_favoritos`
--
ALTER TABLE `tb_favoritos`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;
--
-- AUTO_INCREMENT for table `tb_notificacoes`
--
ALTER TABLE `tb_notificacoes`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;
--
-- AUTO_INCREMENT for table `tb_palavrasdebaixocalao`
--
ALTER TABLE `tb_palavrasdebaixocalao`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `tb_posts`
--
ALTER TABLE `tb_posts`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;
--
-- AUTO_INCREMENT for table `tb_seguidor_seguido`
--
ALTER TABLE `tb_seguidor_seguido`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `tb_tentativas`
--
ALTER TABLE `tb_tentativas`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
--
-- AUTO_INCREMENT for table `tb_usuarios`
--
ALTER TABLE `tb_usuarios`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
