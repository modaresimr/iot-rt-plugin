<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

include_once( 'iot_defaults.php' );

function iot_load_widget()
{
    register_widget('iot_widget');
}
add_action('widgets_init', 'iot_load_widget');
// Creating the widget 
class iot_widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            // Base ID of your widget
            'iot_widget',
            // Widget name will appear in UI
            __('IOT UNI widget', 'iot_widget_domain'),
            // Widget description
            array('description' => __('LIST DEPARTEMNT', 'iot_widget_domain'),)
        );
    }
    // Creating widget front-end
    public function widget($args, $instance)
    {
        $title='';
        if(isset($instance['title']))
            $title = apply_filters('widget_title', $instance['title']);
        // before and after widget arguments are defined by themes
        echo $args['before_widget'];
        if (!empty($title))
            echo $args['before_title'] . $title . $args['after_title'];
        // This is where you run the code and display the output
        //echo __('Hello, World!', 'iot_widget_domain');
        iot_q_list_handler();
        echo $args['after_widget'];
    }
    // Widget Backend 
    public function form($instance)
    {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = __('Title', 'iot_widget_domain');
        }
        // Widget admin form
        ?>
    <p>
        <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
    </p>
<?php
}
// Updating widget replacing old instances with new
public function update($new_instance, $old_instance)
{
    $instance = array();
    $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
    return $instance;
}
} // Class iot_widget ends here






function iot_q_list_handler(){


$terms = get_terms(IOT_TAX_UNIVERSITY,array('parent' => 0,'orderby'=>'name', 'hide_empty' => false));
echo '<input type="text" id="uni-search-box" class="form-control" placeholder="Entrez l\'université pour la recherche">';
echo '<div id="uni-search" class="list-group">';
foreach($terms as $term) {  
   echo '<a class="list-group-item list-group-item-action '.(($_REQUEST[IOT_TAX_UNIVERSITY]??'')==$term->name?'active':'').'" href="'.site_url('/iot-wiki').'?'.IOT_TAX_UNIVERSITY.'='.$term->name.'">'.$term->name.'</a>';
}
echo '</div>';
?>


<script>
$(function(){
	$('#uni-search-box').keyup(function(){	
		var current_query = $('#uni-search-box').val().toUpperCase();
		if (current_query !== "") {
			$("#uni-search .list-group-item").hide();
			$("#uni-search .list-group-item").each(function(){
				var current_keyword = $(this).text().toUpperCase();
				if (current_keyword.indexOf(current_query) >=0) {
					$(this).show();    	 	
				};
			});    	
		} else {
			$("$uni-search .list-group-item").show();
		};
	});
});
</script>
<?php
}
