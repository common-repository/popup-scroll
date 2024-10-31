
    <table class="form-table cmb_metabox">
        <tbody>
            <tr class="cmb-type-radio_inline cmb_id__chch_pusf_time_newsletter cmb-inline">
                <th class="c1"><label for="_chch_pusf_time_newsletter">Newsletter Status:</label></th>

                <td>
                    <?php

$newsletter = get_post_meta( $post->ID, '_chch_pusf_newsletter', true ) ? get_post_meta( $post->ID, '_chch_pusf_newsletter', true ) : 'yes';

?>

                    <ul class="cmb_radio_list cmb_list">
                        <li><input class="cmb_option" name="_chch_pu_newsletter" id="_chch_pusf_time_newsletter1" value="yes" type="radio" <?php

if ( $newsletter == 'yes' )
  echo 'checked';

?> /> <label for="_chch_pusf_time_newsletter1">Active</label></li>

                        <li><input class="cmb_option" name="_chch_pu_newsletter" id="_chch_pusf_time_newsletter2" value="no" type="radio" <?php

if ( $newsletter == 'no' )
  echo 'checked';

?> /> <label for="_chch_pusf_time_newsletter2">Inactive</label></li>
                    </ul>

                    <p class="cmb_metabox_description">Show or hide the newsletter subscribe form.</p>
                </td>
            </tr>

            <tr class="cmb-type-select cmb_id__chch_pusf_time_newsletter_adapter">
                <th class="c1"><label for="_chch_pu_newsletter_adapter">Save E-mails to:</label></th>

                <td><select id="_chch_pusf_save_emails" name="_chch_pusf_save_emails" class="cmb_select">
                    <option selected="selected" value="Email">
                        Email
                    </option>

                    <option disabled="disabled" value="MailChimp">
                        MailChimp (Available in Pro)
                    </option> 
                </select> <a href="http://ch-ch.org/pupro" target="_blank">Get Pro</a></td>
            </tr><?php

$email_option = get_post_meta( $post->ID, '_Email_data', true );
$email = '';
if ( isset( $email_option['email'] ) && !empty( $email_option['email'] ) ) {
  $email = $email_option['email'];
}

?>

            <tr class="cmb-type-select newsletter_adapter_wrapper Email-adapter">
                <th class="c1"><label for="_chch_pu_email">E-mail Address:</label></th>

                <td><input class="cmb_text_medium" name="_chch_pu_email" id="_chch_pu_email" value="<?php

echo $email;

?>" type="text" />
                <br />
                <span class="cmb_metabox_description">Subscription notifications will be sent to this email. If there is no email provided, admin email will be used.</span></td>
            </tr>

            <tr class="cmb-type-select newsletter_adapter_wrapper Email-adapter">
                <th class="c1"><label for="_chch_pu_email">E-mail Notification:</label></th><?php

if ( isset( $email_option['email_message'] ) ) {
  $message = $email_option['email_message'];
} else {
  $message = sprintf( __( "Hello,\n\nA new user has subscribed through: %s.\n\nSubscriber's email: {email}", $this->plugin_slug ), get_bloginfo( 'url' ) );
}

?>

                <td>
                <textarea class="cmb-type-textarea-code" name="_chch_pu_email_message">
<?php

echo $message;

?>
</textarea>
                <br />
                <span class="cmb_metabox_description">This message will be sent when somebody signs up. Use Field Id's (in curly brackets) defined in "E-Mail Fields" section below.</span></td>
            </tr>

            <tr class="cmb-type-select newsletter_adapter_wrapper Email-adapter">
                <th class="c1"><label for="_chch_pu_email">E-mail Fields:</label></th>

                <td>
                    <?php

$i = 0;
if ( isset( $email_option['fields'] ) ):

?>

                    <table class="chch-pu-repeater">
                        <tbody>
                            <?php

  foreach ( ( array )$email_option['fields'] as $field ):

    $field_types = array(
      'email' => 'Email',
      'phone' => 'Phone',
      'text' => 'Text' );

?>

                            <tr class="chch-pu-reapeter-fields">
                                <td class="field-count"><?php

    echo $i + 1;

?></td>

                                <td>
                                    <div class="chch-pu-reapeter-field-wrapper chch-pu-select-wrapper">
                                        <label for="email-fields[<?php

    echo $i;

?>][type]">Field Type:</label> <select name="email-fields[<?php

    echo $i;

?>][type]" class="chch-pu-repeater-field chch-pu-repeater-select">
                                            <?php

    foreach ( $field_types as $id => $val ) {
      $selected = '';
      if ( isset( $field['type'] ) && $id == $field['type'] ) {
        $selected = 'selected';
      }

      printf( "\t<option value=\"%s\" %s>%s</option>\n", $id, $selected, $val );

    }

?>
                                        </select>
                                    </div>

                                    <div class="chch-pu-reapeter-field-wrapper chch-pu-text-wrapper">
                                        <label for="email-fields[<?php

    echo $i;

?>][id]">Field Id:</label> <?php

    printf( "\t<input type=\"text\" name=\"email-fields[%s][id]\"  class=\"chch-pu-repeater-field chch-pu-reapeter-text\" value=\"%s\"/>", $i, $field['id'] );

?>
                                    </div>

                                    <div class="chch-pu-reapeter-field-wrapper chch-pu-text-wrapper">
                                        <label for="email-fields[<?php

    echo $i;

?>][name]">Placeholder:</label> <?php

    printf( "\t<input type=\"text\" name=\"email-fields[%s][name]\"  class=\"chch-pu-repeater-field chch-pu-reapeter-text\" value=\"%s\"/>", $i, $field['name'] );

?>
                                    </div>

                                    <div class="chch-pu-reapeter-field-wrapper chch-pu-checkbox-wrapper">
                                        <?php

    $checked = '';
    if ( isset( $field['req'] ) ) {
      $checked = 'checked';
    }
    printf( "\t<input type=\"checkbox\" name=\"email-fields[%s][req]\"  class=\"chch-pu-repeater-field chch-pu-reapeter-checkbox\" %s />", $i, $checked );

?><span>Required</span>
                                    </div>
                                </td>

                                <td ><?php

    $hide = '';
    if ( $i == 0 ) {
      $hide = 'hide-section';
    }

?><a href="#" class="delete-email-field <?php

    echo $hide;

?>"> <span class="dashicons dashicons-dismiss"></span></a></td>
                            </tr><?php

    $i++;
  endforeach;

?>
                        </tbody>
                    </table><?php

  else:

?>

                    <table class="chch-pu-repeater">
                        <tbody>
                            <tr class="chch-pu-reapeter-fields">
                                <td class="field-count">1</td>

                                <td>
                                    <div class="chch-pu-reapeter-field-wrapper chch-pu-select-wrapper">
                                        <label for="email-fields[0][type]">Field Type:</label> <select name="email-fields[0][type]" class="chch-pu-repeater-field chch-pu-repeater-select">
                                            <option value="email">
                                                Email
                                            </option>

                                            <option value="phone">
                                                Phone
                                            </option>

                                            <option value="text">
                                                Text
                                            </option>
                                        </select>
                                    </div>

                                    <div class="chch-pu-reapeter-field-wrapper chch-pu-text-wrapper">
                                        <label for="email-fields[0][id]">Field Id:</label> <input type="text" name="email-fields[0][id]" class="chch-pu-repeater-field chch-pu-repeater-text" value="email" />
                                    </div>

                                    <div class="chch-pu-reapeter-field-wrapper chch-pu-text-wrapper">
                                        <label for="email-fields[0][name]">Placeholder:</label> <input type="text" name="email-fields[0][name]" class="chch-pu-repeater-field chch-pu-repeater-text" value="Email" />
                                    </div>

                                    <div class="chch-pu-reapeter-field-wrapper chch-pu-checkbox-wrapper">
                                        <input type="checkbox" name="email-fields[0][req]" class="chch-pu-repeater-field chch-pu-repeater-checkbox" /> <span>Required</span>
                                    </div>
                                </td>

                                <td class="c2"><a href="#" class="delete-email-field hide-section"><span class="dashicons dashicons-dismiss"></span></a></td>
                            </tr>
                        </tbody>
                    </table><?php

  endif;

?><input type="button" id="chch-pu-add-field" class="button button-primary" value="Add Field" data-field-count="<?php

    echo $i;

?>" />
                </td>
            </tr>
        </tbody>
    </table> 
    <input type="hidden" name="chch-pusf-newsletter-nonce" value="<?php

    echo wp_create_nonce( 'chch-pusf-nonce' );

?>" />
