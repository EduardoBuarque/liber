<?php
/**
 * Action gerada pelo template do Bake para Controller.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2009, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.console.libs.template.objects
 * @since         CakePHP(tm) v 1.3
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
require_once dirname(dirname(__FILE__)) . DS . 'inflexao.php';
?>

	function <?php echo $admin ?>index() {
		$this-><?php echo $currentModelName ?>->recursive = 0;
		$this->set('<?php echo $pluralName ?>', $this->paginate());
	}

	function <?php echo $admin ?>view($id = null) {
		if (!$id) {
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(sprintf(__('%s inválido.'), '<?php echo Inflexao::acentos(ucfirst(strtolower($singularHumanName))); ?>'));
			$this->redirect(array('action' => 'index'));
<?php else: ?>
			$this->flash(sprintf(__('%s inválido.'), '<?php echo Inflexao::acentos(ucfirst(strtolower($singularHumanName))); ?>'), array('action' => 'index'));
<?php endif; ?>
		}
		$this->set('<?php echo $singularName; ?>', $this-><?php echo $currentModelName; ?>->read(null, $id));
	}

<?php $compact = array(); ?>
	function <?php echo $admin ?>add() {
		if (!empty($this->data)) {
			$this-><?php echo $currentModelName; ?>->create();
			if ($this-><?php echo $currentModelName; ?>->save($this->data)) {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(sprintf(__('O %s foi salvo.'), '<?php echo Inflexao::acentos(strtolower($singularHumanName)); ?>'));
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(sprintf(__('%s salvo.'), '<?php echo Inflexao::acentos(ucfirst(strtolower($currentModelName))); ?>'), array('action' => 'index'));
<?php endif; ?>
			} else {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(sprintf(__('O %s não pode ser salvo. Por favor, tente novamente.'), '<?php echo Inflexao::acentos(strtolower($singularHumanName)); ?>'));
<?php endif; ?>
			}
		}
<?php
	foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
		foreach ($modelObj->{$assoc} as $associationName => $relation):
			if (!empty($associationName)):
				$otherModelName = $this->_modelName($associationName);
				$otherPluralName = $this->_pluralName($associationName);
				echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
				$compact[] = "'{$otherPluralName}'";
			endif;
		endforeach;
	endforeach;
	if (!empty($compact)):
		echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
	endif;
?>
	}

<?php $compact = array(); ?>
	function <?php echo $admin; ?>edit($id = null) {
		if (!$id && empty($this->data)) {
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(sprintf(__('%s inválido.'), '<?php echo Inflexao::acentos(ucfirst(strtolower($singularHumanName))); ?>'));
			$this->redirect(array('action' => 'index'));
<?php else: ?>
			$this->flash(sprintf(__('%s inválido.'), '<?php echo Inflexao::acentos(ucfirst(strtolower($singularHumanName))); ?>'), array('action' => 'index'));
<?php endif; ?>
		}
		if (!empty($this->data)) {
			if ($this-><?php echo $currentModelName; ?>->save($this->data)) {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(sprintf(__('O %s foi salvo.'), '<?php echo Inflexao::acentos(strtolower($singularHumanName)); ?>'));
				$this->redirect(array('action' => 'index'));
<?php else: ?>
				$this->flash(sprintf(__('O %s foi salvo.'), '<?php echo Inflexao::acentos(strtolower($singularHumanName)); ?>'), array('action' => 'index'));
<?php endif; ?>
			} else {
<?php if ($wannaUseSession): ?>
				$this->Session->setFlash(sprintf(__('O %s não pode ser salvo. Por favor, tente novamente.'), '<?php echo Inflexao::acentos(strtolower($singularHumanName)); ?>'));
<?php endif; ?>
			}
		}
		if (empty($this->data)) {
			$this->data = $this-><?php echo $currentModelName; ?>->read(null, $id);
		}
<?php
		foreach (array('belongsTo', 'hasAndBelongsToMany') as $assoc):
			foreach ($modelObj->{$assoc} as $associationName => $relation):
				if (!empty($associationName)):
					$otherModelName = $this->_modelName($associationName);
					$otherPluralName = $this->_pluralName($associationName);
					echo "\t\t\${$otherPluralName} = \$this->{$currentModelName}->{$otherModelName}->find('list');\n";
					$compact[] = "'{$otherPluralName}'";
				endif;
			endforeach;
		endforeach;
		if (!empty($compact)):
			echo "\t\t\$this->set(compact(".join(', ', $compact)."));\n";
		endif;
	?>
	}

	function <?php echo $admin; ?>delete($id = null) {
		if (!$id) {
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(sprintf(__('ID inválido para %s.'), '<?php echo Inflexao::acentos(strtolower($singularHumanName)); ?>'));
			$this->redirect(array('action'=>'index'));
<?php else: ?>
			$this->flash(sprintf(__('%s inválido.'), '<?php echo Inflexao::acentos(ucfirst(strtolower($singularHumanName))); ?>'), array('action' => 'index'));
<?php endif; ?>
		}
		if ($this-><?php echo $currentModelName; ?>->delete($id)) {
<?php if ($wannaUseSession): ?>
			$this->Session->setFlash(sprintf(__('%s excluído.'), '<?php echo Inflexao::acentos(ucfirst(strtolower($singularHumanName))); ?>'));
			$this->redirect(array('action'=>'index'));
<?php else: ?>
			$this->flash(sprintf(__('%s excluído.'), '<?php echo Inflexao::acentos(ucfirst(strtolower($singularHumanName))); ?>'), array('action' => 'index'));
<?php endif; ?>
		}
<?php if ($wannaUseSession): ?>
		$this->Session->setFlash(sprintf(__('%s não pode ser excluído.'), '<?php echo Inflexao::acentos(ucfirst(strtolower($singularHumanName))); ?>'));
<?php else: ?>
		$this->flash(sprintf(__('%s não pode ser excluído.'), '<?php echo Inflexao::acentos(ucfirst(strtolower($singularHumanName))); ?>'), array('action' => 'index'));
<?php endif; ?>
		$this->redirect(array('action' => 'index'));
	}