<?php
/*
Plugin Name: Syrus Cookie Law
*/


/* SEZIONE PER L'INIZIALIZZAZIONE DEI FILE NECESSARI AL PLUGIN */

//funzione per la registrazione degli scripts e degli styles necessari
function syrus_cookie_law_register_scripts() {
  //aggiungo il js e i css necessari
  wp_register_script("syrus_cookie_law_cookie", '/wp-content/plugins/syrus-cookie-law/frontend/cookieconsent.js');
  //aggiungo i fogli di stile necessari
  wp_register_style("syrus_cookie_law_animation", '/wp-content/plugins/syrus-cookie-law/frontend/styles/animation.css');
  wp_register_style("syrus_cookie_law_base", '/wp-content/plugins/syrus-cookie-law/frontend/styles/base.css');
  wp_register_style("syrus_cookie_law_layout", '/wp-content/plugins/syrus-cookie-law/frontend/styles/layout.css');
  wp_register_style("syrus_cookie_law_media", '/wp-content/plugins/syrus-cookie-law/frontend/styles/media.css');
  wp_register_style("syrus_cookie_law_classic", '/wp-content/plugins/syrus-cookie-law/frontend/styles/themes/classic.css');
  wp_register_style("syrus_cookie_law_edgeless", '/wp-content/plugins/syrus-cookie-law/frontend/styles/themes/edgeless.css');

}

add_action('wp_loaded', 'syrus_cookie_law_register_scripts');

//funzione per l'enqueue degli script e degli styles necessari
function syrus_cookie_law_enqueue_scripts() {
  //aggiungo il js e i css necessari
  wp_enqueue_script("syrus_cookie_law_cookie");
  //aggiungo i fogli di stile necessari
  wp_enqueue_style("syrus_cookie_law_animation");
  wp_enqueue_style("syrus_cookie_law_base");
  wp_enqueue_style("syrus_cookie_law_layout");
  wp_enqueue_style("syrus_cookie_law_media");
  wp_enqueue_style("syrus_cookie_law_classic");
  wp_enqueue_style("syrus_cookie_law_edgeless");
}

add_action('wp_enqueue_scripts', 'syrus_cookie_law_enqueue_scripts');




/* SEZIONE PER LA PAGINA DI CONFIGURAZIONE DEL PLUGIN */

//funzione per la registrazione delle settings
function syrus_cookie_law_register_settings() {
  //aggiungo il setting group
  register_setting('syrus_cookie_law_setting_option', 'syrus_cookie_law_setting_option');
  //aggiungo la sezione
  add_settings_section(
    'syrus_cookie_law_setting_section',
    'Impostazioni Syrus Cookie Law',
    'syrus_cookie_law_setting_section_cb',
     'syrus_cookie_law_setting_page'
  );
  //aggiungo un campo per il testo del cookie ed uno per il link di riferimento per le maggiori info
  add_settings_field(
    'syrus_cookie_law_setting_field_cookie_text',
    'Testo da mostrare nel banner',
    'syrus_cookie_law_setting_field_cookie_text_cb',
    'syrus_cookie_law_setting_page',
    'syrus_cookie_law_setting_section',
    array(
      'label_for' => 'syrus_cookie_law_setting_field_cookie_text'
    )
  );
  add_settings_field(
    'syrus_cookie_law_setting_field_cookie_link',
    'Link per maggiori informazioni',
    'syrus_cookie_law_setting_field_cookie_link_cb',
    'syrus_cookie_law_setting_page',
    'syrus_cookie_law_setting_section',
    array(
      'label_for' => 'syrus_cookie_law_setting_field_link_text'
    )
  );
}

function syrus_cookie_law_setting_field_cookie_text_cb($args) {
  //recupero le opzioni del plugin
  $options = get_option('syrus_cookie_law_setting_option');
  //stampo l'input field
  ?>
  <textarea name="<?php echo $args['label_for']; ?>" rows="8" cols="40"><?php if(!is_null($options[$args['label_for']])) echo $options[$args['label_for']]; ?></textarea>
  <?php
}

function syrus_cookie_law_setting_field_cookie_link_cb($args) {
  //recupero le opzioni del plugin
  $options = get_option('syrus_cookie_law_setting_option');
  //stampo l'input field
  ?>
  <input name="<?php echo $args['label_for']; ?>" value="<?php if(!is_null($options[$args['l abel_for']])) echo $options[$args['label_for']]; ?>">
  <?php
}

function syrus_cookie_law_setting_section_cb() {
  //non server stampi nulla
}

//funzione per la stampa della pagina di gestione delle impostazioni del cookie law Plugin
function syrus_cookie_law_setting_page_html() {
  //aggiungo il messaggio di modifica se submit del form
  if(isset($_GET['settings-updated'])) {
    add_settings_error('syrus_cookie_law_setting_message','syrus_cookie_law_setting_message', 'Impostazioni Salvate', 'updated' );
  }

  settings_errors('syrus_cookie_law_setting_message');

  ?>
  <div class="wrap">
    <form  action="options.php" method="post">
      <?php
        settings_fields('syrus_cookie_law_setting_option');
        do_settings_sections('syrus_cookie_law_setting_page');
       ?>
       <?php submit_button("Salva Impostazioni"); ?>
    </form>

  </div>
  <?php
}

function syrus_cookie_law_register_pages() {
  //aggiungo la pagina di gestione delle impostazioni del cookie
  add_menu_page(
    'Syrus Cookie Law',
    'Syrus Cookie Law',
    'read',
    'syrus_cookie_law_setting_page',
    'syrus_cookie_law_setting_page_html'
  );
}

add_action("admin_init", 'syrus_cookie_law_register_pages');


/* SEZIONE PER L'INIZIALIZZAZIONE DELLO SCRIPT DI ATTIVAZIONE DEL COOKIE LAW */
//aggiungo lo script di inizializzazione del cookie law nell'head di wp
function syrus_cookie_law_initialize() {
  ?>
  <script>
    window.cookieconsent.initialise({
      container: document.getElementById("content"),
      palette:{
        popup: {background: "#fff"},
        button: {background: "#aa0000"},
      },
      revokable:true,
      onStatusChange: function(status) {
        console.log(this.hasConsented() ?
          'enable cookies' : 'disable cookies');
      },
      law: {
        regionalLaw: false,
      },
      location: true,
    });
  </script>
  <?php
}
//registro lo script di inizializzazione nell'head di wp
add_action('wp_footer', 'syrus_cookie_law_initialize');
