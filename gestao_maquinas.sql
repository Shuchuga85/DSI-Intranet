-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Tempo de geração: 14-Jul-2024 às 23:10
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `gestao_maquinas`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `checklist`
--

CREATE TABLE `checklist` (
  `check_id` int(11) NOT NULL,
  `id_maquina` int(11) NOT NULL,
  `list_fields` varchar(600) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `checklist`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `computador`
--

CREATE TABLE `computador` (
  `pc_id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `pc_name` varchar(255) DEFAULT NULL,
  `pc_mac` varchar(40) DEFAULT NULL,
  `pc_so` varchar(40) DEFAULT NULL,
  `pc_cpu` varchar(40) DEFAULT NULL,
  `pc_mem` varchar(40) DEFAULT NULL,
  `pc_drive` varchar(40) DEFAULT NULL,
  `pc_serial` varchar(40) DEFAULT NULL,
  `admin_user` varchar(40) DEFAULT NULL,
  `admin_pass` varchar(64) DEFAULT NULL,
  `pc_malfunctions` varchar(200) DEFAULT NULL,
  `username` varchar(40) DEFAULT NULL,
  `user_pass` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `computador`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `flags`
--

CREATE TABLE `flags` (
  `flag_id` int(11) NOT NULL,
  `flag_type` varchar(50) NOT NULL,
  `destination` int(11) NOT NULL,
  `message` varchar(400) DEFAULT NULL,
  `origin` varchar(100) DEFAULT NULL,
  `seen` tinyint(1) NOT NULL DEFAULT 0,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `form_values`
--

CREATE TABLE `form_values` (
  `id_pedido` int(11) NOT NULL,
  `act_empresa` varchar(40) DEFAULT NULL,
  `comp_nome` varchar(40) DEFAULT NULL,
  `alt_nome` varchar(40) DEFAULT NULL,
  `act_departamento` varchar(40) DEFAULT NULL,
  `superior` varchar(40) DEFAULT NULL,
  `pc_note` varchar(200) DEFAULT NULL,
  `penkanguru` tinyint(1) DEFAULT NULL,
  `cr_email` tinyint(1) DEFAULT NULL,
  `sug_email` varchar(40) DEFAULT NULL,
  `type_mail` varchar(40) DEFAULT NULL,
  `type_tele` varchar(40) DEFAULT NULL,
  `card_tele` tinyint(1) DEFAULT NULL,
  `dados_tele` varchar(40) DEFAULT NULL,
  `cr_sap` tinyint(1) DEFAULT NULL,
  `easy_schedule` tinyint(1) DEFAULT NULL,
  `server_user` varchar(40) DEFAULT NULL,
  `vpn` tinyint(1) DEFAULT NULL,
  `office_lang` varchar(40) DEFAULT NULL,
  `alt_office_lang` varchar(40) DEFAULT NULL,
  `services` varchar(40) DEFAULT NULL,
  `alt_services` varchar(40) DEFAULT NULL,
  `cyber_card` tinyint(1) DEFAULT NULL,
  `cyber_profile` varchar(40) DEFAULT NULL,
  `obs` varchar(200) DEFAULT NULL,
  `data_pedido` timestamp NOT NULL DEFAULT current_timestamp(),
  `status_pedido` varchar(40) DEFAULT NULL,
  `contact_email` varchar(40) DEFAULT NULL,
  `contact_nome` varchar(40) DEFAULT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `form_values`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `intranet_param`
--

CREATE TABLE `intranet_param` (
  `id` int(11) NOT NULL DEFAULT 1,
  `ldap_host` varchar(40) NOT NULL DEFAULT 'ldap.example.com',
  `ldap_port` varchar(30) NOT NULL DEFAULT '389',
  `ldap_domain` varchar(40) NOT NULL DEFAULT '@example.com',
  `ldap_base` varchar(50) NOT NULL DEFAULT 'dc=example,dc=com',
  `email_sender_user` varchar(30) NOT NULL DEFAULT 'sender@example.com',
  `email_sender_pass` varchar(64) NOT NULL DEFAULT '',
  `email_receiver` varchar(500) NOT NULL DEFAULT 'receiver@example.com',
  `email_smtp_host` varchar(40) NOT NULL DEFAULT 'smtp.example.com',
  `email_smtp_port` varchar(40) NOT NULL DEFAULT '587',
  `email_name` varchar(40) NOT NULL DEFAULT 'CyberSculpt - Intranet',
  `email_subject` varchar(150) NOT NULL DEFAULT 'CyberSculpt - Intranet, Nova maquina Requisitada.',
  `email_attachment_name` varchar(40) NOT NULL DEFAULT 'Formulario',
  `message_title` varchar(150) NOT NULL DEFAULT 'CyberSculpt - Intranet, atualizacao de status em seu pedido de novo utilizador.',
  `email_cc` varchar(500) DEFAULT NULL,
  `message_cc` varchar(500) DEFAULT NULL,
  `activate_receiver_feedback` tinyint(1) NOT NULL DEFAULT 0,
  `activate_return_postform` tinyint(1) NOT NULL DEFAULT 0,
  `settings_user` varchar(40) NOT NULL DEFAULT 'CHANGE_ME_USER',
  `settings_pass` varchar(40) NOT NULL DEFAULT 'CHANGE_ME_PASSWORD',
  `email_smtp_encryption` varchar(10) NOT NULL DEFAULT 'tls',
  `secretkey` varchar(16) NOT NULL DEFAULT 'CHANGE_ME_KEY',
  `activate_prebuilt_message` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `intranet_param`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `ldap`
--

CREATE TABLE `ldap` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `givenname` varchar(100) NOT NULL,
  `samaccountname` varchar(100) NOT NULL,
  `memberof` varchar(100) NOT NULL,
  `mail` varchar(100) NOT NULL,
  `password` varchar(126) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `ldap`
--

-- --------------------------------------------------------

--
-- Estrutura stand-in para vista `maquinasview`
-- (Veja abaixo para a view atual)
--
CREATE TABLE `maquinasview` (
`id_pedido` int(11)
,`act_empresa` varchar(40)
,`comp_nome` varchar(40)
,`alt_nome` varchar(40)
,`act_departamento` varchar(40)
,`superior` varchar(40)
,`pc_note` varchar(200)
,`penkanguru` tinyint(1)
,`cr_email` tinyint(1)
,`sug_email` varchar(40)
,`type_mail` varchar(40)
,`type_tele` varchar(40)
,`card_tele` tinyint(1)
,`dados_tele` varchar(40)
,`cr_sap` tinyint(1)
,`easy_schedule` tinyint(1)
,`server_user` varchar(40)
,`vpn` tinyint(1)
,`office_lang` varchar(40)
,`alt_office_lang` varchar(40)
,`services` varchar(40)
,`alt_services` varchar(40)
,`cyber_card` tinyint(1)
,`cyber_profile` varchar(40)
,`obs` varchar(200)
,`data_pedido` timestamp
,`status_pedido` varchar(40)
,`contact_email` varchar(40)
,`contact_nome` varchar(40)
,`last_update` timestamp
,`pc_id` int(11)
,`pedido_id` int(11)
,`pc_name` varchar(255)
,`pc_mac` varchar(40)
,`pc_so` varchar(40)
,`pc_cpu` varchar(40)
,`pc_mem` varchar(40)
,`pc_drive` varchar(40)
,`pc_serial` varchar(40)
,`admin_user` varchar(40)
,`admin_pass` varchar(64)
,`pc_malfunctions` varchar(200)
,`username` varchar(40)
,`user_pass` varchar(64)
,`check_id` int(11)
,`id_maquina` int(11)
,`list_fields` varchar(600)
);

-- --------------------------------------------------------

--
-- Estrutura da tabela `notifications`
--

CREATE TABLE `notifications` (
  `ntf_id` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `body` varchar(400) DEFAULT NULL,
  `origin` varchar(100) DEFAULT NULL,
  `img` varchar(100) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT curtime(),
  `pedido_id` int(11) NOT NULL,
  `destination` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `notifications`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` text DEFAULT NULL,
  `block` tinyint(1) DEFAULT NULL,
  `lang` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `users`
--

-- --------------------------------------------------------

--
-- Estrutura para vista `maquinasview`
--
DROP TABLE IF EXISTS `maquinasview`;

CREATE ALGORITHM=UNDEFINED SQL SECURITY INVOKER VIEW `maquinasview`  AS SELECT `form_values`.`id_pedido` AS `id_pedido`, `form_values`.`act_empresa` AS `act_empresa`, `form_values`.`comp_nome` AS `comp_nome`, `form_values`.`alt_nome` AS `alt_nome`, `form_values`.`act_departamento` AS `act_departamento`, `form_values`.`superior` AS `superior`, `form_values`.`pc_note` AS `pc_note`, `form_values`.`penkanguru` AS `penkanguru`, `form_values`.`cr_email` AS `cr_email`, `form_values`.`sug_email` AS `sug_email`, `form_values`.`type_mail` AS `type_mail`, `form_values`.`type_tele` AS `type_tele`, `form_values`.`card_tele` AS `card_tele`, `form_values`.`dados_tele` AS `dados_tele`, `form_values`.`cr_sap` AS `cr_sap`, `form_values`.`easy_schedule` AS `easy_schedule`, `form_values`.`server_user` AS `server_user`, `form_values`.`vpn` AS `vpn`, `form_values`.`office_lang` AS `office_lang`, `form_values`.`alt_office_lang` AS `alt_office_lang`, `form_values`.`services` AS `services`, `form_values`.`alt_services` AS `alt_services`, `form_values`.`cyber_card` AS `cyber_card`, `form_values`.`cyber_profile` AS `cyber_profile`, `form_values`.`obs` AS `obs`, `form_values`.`data_pedido` AS `data_pedido`, `form_values`.`status_pedido` AS `status_pedido`, `form_values`.`contact_email` AS `contact_email`, `form_values`.`contact_nome` AS `contact_nome`, `form_values`.`last_update` AS `last_update`, `computador`.`pc_id` AS `pc_id`, `computador`.`pedido_id` AS `pedido_id`, `computador`.`pc_name` AS `pc_name`, `computador`.`pc_mac` AS `pc_mac`, `computador`.`pc_so` AS `pc_so`, `computador`.`pc_cpu` AS `pc_cpu`, `computador`.`pc_mem` AS `pc_mem`, `computador`.`pc_drive` AS `pc_drive`, `computador`.`pc_serial` AS `pc_serial`, `computador`.`admin_user` AS `admin_user`, `computador`.`admin_pass` AS `admin_pass`, `computador`.`pc_malfunctions` AS `pc_malfunctions`, `computador`.`username` AS `username`, `computador`.`user_pass` AS `user_pass`, `checklist`.`check_id` AS `check_id`, `checklist`.`id_maquina` AS `id_maquina`, `checklist`.`list_fields` AS `list_fields` FROM ((`form_values` left join `computador` on(`computador`.`pedido_id` = `form_values`.`id_pedido`)) left join `checklist` on(`checklist`.`id_maquina` = `computador`.`pc_id`))  ;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `checklist`
--
ALTER TABLE `checklist`
  ADD PRIMARY KEY (`check_id`),
  ADD KEY `id_maquina` (`id_maquina`);

--
-- Índices para tabela `computador`
--
ALTER TABLE `computador`
  ADD PRIMARY KEY (`pc_id`),
  ADD KEY `pedido_id` (`pedido_id`);

--
-- Índices para tabela `flags`
--
ALTER TABLE `flags`
  ADD PRIMARY KEY (`flag_id`),
  ADD KEY `destination` (`destination`);

--
-- Índices para tabela `form_values`
--
ALTER TABLE `form_values`
  ADD PRIMARY KEY (`id_pedido`);

--
-- Índices para tabela `intranet_param`
--
ALTER TABLE `intranet_param`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `ldap`
--
ALTER TABLE `ldap`
  ADD PRIMARY KEY (`user_id`);

--
-- Índices para tabela `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`ntf_id`);

--
-- Índices para tabela `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `checklist`
--
ALTER TABLE `checklist`
  MODIFY `check_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `computador`
--
ALTER TABLE `computador`
  MODIFY `pc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `flags`
--
ALTER TABLE `flags`
  MODIFY `flag_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `form_values`
--
ALTER TABLE `form_values`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `ldap`
--
ALTER TABLE `ldap`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `notifications`
--
ALTER TABLE `notifications`
  MODIFY `ntf_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `checklist`
--
ALTER TABLE `checklist`
  ADD CONSTRAINT `checklist_ibfk_1` FOREIGN KEY (`id_maquina`) REFERENCES `computador` (`pc_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `computador`
--
ALTER TABLE `computador`
  ADD CONSTRAINT `computador_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `form_values` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `flags`
--
ALTER TABLE `flags`
  ADD CONSTRAINT `flags_ibfk_1` FOREIGN KEY (`destination`) REFERENCES `form_values` (`id_pedido`) ON DELETE CASCADE ON UPDATE CASCADE;
-- Placeholder configuration only; configure locally before use.
INSERT INTO `intranet_param` (`id`) VALUES (1);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
