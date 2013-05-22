<?= $this->element('admin_header'); ?>
<div class="gifts index">
	<h2><?php echo __('Campaigns'); ?></h2>
	<table cellpadding="0" cellspacing="1" border="1px">
	<tr padding="2px 2px 2px 2px">
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('campaign_name'); ?></th>
			<th><?php echo $this->Paginator->sort('product_id'); ?></th>
			<th><?php echo $this->Paginator->sort('start_date'); ?></th>
			<th><?php echo $this->Paginator->sort('end_date'); ?></th>
			<th><?php echo $this->Paginator->sort('enable'); ?></th>
			<th><?php echo $this->Paginator->sort('On Landing Page'); ?></th>
			<th><?php echo $this->Paginator->sort('Redirect'); ?></th>


			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php

	foreach ($campaigns as $campaign): ?>
	<tr>
		<td><?php echo h($campaign['Campaign']['id']); ?>&nbsp;</td>
		<td><?php echo h($campaign['Campaign']['campaign_name']); ?>&nbsp;</td>

		<td>
			<?php echo $this->Html->link($campaign['Campaign']['product_id'], array('controller' => 'campaigns', 'action' => 'view', $campaign['Campaign']['id'])); ?>
		</td>
		<td><?php echo h($campaign['Campaign']['start_date']); ?>&nbsp;</td>
		<td><?php echo h($campaign['Campaign']['end_date']); ?>&nbsp;</td>

		
		<td><?php echo h($campaign['Campaign']['enable']); ?>&nbsp;</td>
		<td><?php echo h($campaign['Campaign']['on_landing_page']); ?>&nbsp;</td>
		<td><?php 
			if($campaign['Campaign']['redirect_to'] == 2 )
			{
				echo "Campaign";?>&nbsp;</td><?php
			}
			else
			{
				echo "Reminder";?>&nbsp;</td><?php
			}?>
		
		
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $campaign['Campaign']['id'])); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $campaign['Campaign']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $campaign['Campaign']['id']), null, __('Are you sure you want to delete # %s?', $campaign['Campaign']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<div class="paging">
	<?php
	    if ($this->Paginator->hasPrev()) {
		echo $this->Paginator->prev($this->html->image('35x35_prev.png' , array("title" => "Prev")), array('escape' => false), null, array('class' => 'prev disabled'));
	}
		 if ($this->Paginator->hasNext()) {
		 echo $this->Paginator->next($this->html->image('35x35_next.png' , array("title" => "next")),array('escape' => false), null, array('class' => 'next disabled'));
	}
	?>
    </div>
    <div style="width:auto;height:auto;margin-top:-18px;margin-left:100px;" >
		<br><?php echo $this->Paginator->numbers(array('separator' => ' | ','modulus'=>'10','first'=>'First Page ','last'=>' Last Page'));?>
    </div> 
    <div style="width:auto;height:auto;margin-top:20px;margin-left:50px;" >
			<?php
			echo $this->Paginator->counter(array(
				'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
				));
				?>	
    </div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('New Campaign'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Campaign'), array('controller' => 'campaigns', 'action' => 'admin')); ?> </li>
		
	</ul>
</div>
