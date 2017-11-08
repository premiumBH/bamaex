<?php
/**
 * Created by PhpStorm.
 * User: QasimRafique
 * Date: 10/24/2017
 * Time: 5:53 PM
 */
        $this->load->view('layout/header');

        $this->load->view('layout/container');

		?>


    <form method="post" enctype="multipart/form-data">
        <div class="row">

            <div class="row">
                <div class="col-md-6">

                    <div class="form-group">
                        <input class="btn green" type="file" name="Template" value="upload CSV">
                        <input class="btn green" type="hidden" name="client_id" value="<?php echo $client_id;?>">
                    </div>


                </div>
                <div class="col-md-6">

                    <div class="col-md-3">
                        <div class="form-group">
                            <input class="btn green" value="View File" type="submit" name="submit">
                        </div>
                    </div>
                    <?php
                    if(isset($success))
                    {
                        ?>
                        <div class="col-md-3">
                            <div class="form-group">
                                <input class="btn green" value="Confirm Order" type="submit" name="orderData">
                            </div>
                        </div>
                        <?php
                    }
                    ?>


                    <div class="col-md-3">
                        <div class="form-group">
                            <a href="<?php echo base_url();?>AjentOrders\download_template" download="Template.xlsx"><input class="btn green" value="Download Template" type="button"></a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <?php
        if(isset($message))
        {
            ?>
            <div class="row">
                <p class="info"><?php echo $message; ?></p>
            </div>
            <?php
        }
        ?>
        <div class="row">
            <div class="col-md-12">

                <div class="portlet light portlet-fit bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="icon-settings font-red"></i>
                            <span class="caption-subject font-red sbold uppercase">Consignments origination - Bulk file upload function</span>

                        </div>
                    </div>
                    <?php
                    if(isset($columns) )
                    {
                        ?>
                        <div class="portlet-body">
                            <table class="table table-striped table-hover table-bordered" id="sample_editable_1" name="data_table">
                                <thead>
                                <tr>
                                    <?php
                                    $col_counter = 0;
                                    foreach ($columns as $column)
                                    {
                                        if($column == '')
                                            break
                                        ?>
                                        <th><?php echo $column;?></th>

                                        <?php
                                        $col_counter = $col_counter + 1;
                                    }
                                    ?>      <th>Order Amount</th>
                                    <th> Edit </th>
                                    <th> Delete </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(isset($rows))
                                {
                                    $counter = 0;
                                    $row_count = 0;
                                    foreach($rows as $row)
                                    {
                                        $counter = 0;
                                        $flag = 0;
                                        echo '<tr>';
                                        foreach($row as $val)
                                        {
                                            if($counter ==  $col_counter)
                                                break;
                                            ?>

                                            <td><input type="hidden" name="row_<?php echo $row_count;?>[]" value="<?php echo $val;?>"><?php echo $val;?></td>


                                            <?php
                                            $counter = $counter + 1;
                                            $flag = 1;

                                        }
                                        ?>
                                        <td><?php echo $rows_amount[$row_count]; ?></td>
                                        <?php
                                        if($flag == 1)
                                        {
                                            ?>
                                            <td>
                                                <a class="edit" href="javascript:;"> Edit </a>
                                            </td>
                                            <td>
                                                <a class="delete" href="javascript:;"> Delete </a>
                                            </td>
                                            <?php
                                        }
                                        echo '</tr>';
                                        $row_count = $row_count + 1;
                                    }
                                }
                                ?>

                                </tbody>
                            </table>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </form>
<?php
$this->load->view('layout/footer');

?>