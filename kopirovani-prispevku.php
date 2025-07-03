<?php
/**
 * Logika pro kopírování textu příspěvku.
 * Původně z pluginu, nyní integrováno do šablony.
 */

// Zabraň přímému přístupu
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Přidá ikonu pro kopírování pod vodorovnou čáru v obsahu.
 * Ikona bude vložena dovnitř prvního odstavce za čárou, aby ji text obtékal.
 */
function jcp_add_icon_to_content( $content ) {
    if ( is_singular('post') && in_the_loop() && is_main_query() ) {

        // Ikona, kterou chceme vložit
        $icon_html = '<span class="jcp-icon-container"><a href="#" class="jcp-copy-icon" title="Zkopírovat text do schránky"><i class="fa fa-clipboard" aria-hidden="true"></i></a></span>';

        // Regulární výraz, který najde první <hr> a první <p> za ním.
        $pattern = '/(<hr[^>]*>.*?)(<p[^>]*>)/is';

        // Zkontrolujeme, zda vzor v obsahu existuje
        if ( preg_match( $pattern, $content ) ) {
            // Nahradíme nalezený vzor tak, že za otevírací <p> tag vložíme naši ikonu.
            $replacement = '$1$2' . $icon_html;
            $content = preg_replace( $pattern, $replacement, $content, 1 );
        }
    }
    return $content;
}
// Tento filtr zajistí vložení ikony do obsahu příspěvku
add_filter( 'the_content', 'jcp_add_icon_to_content', 20 );


/**
 * Vloží JavaScript pro obsluhu ikony do patičky stránky.
 */
function jcp_add_copy_script_js() {
    // Vkládáme pouze na stránce detailu příspěvku
    if ( is_single() ) {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            const copyButton = document.querySelector('.jcp-copy-icon');

            if (copyButton) {
                copyButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Najdeme celý kontejner s obsahem článku
                    const contentWrapper = this.closest('.entry-content');
                    if (!contentWrapper) return;

                    // Vytvoříme dočasný klon obsahu
                    const contentClone = contentWrapper.cloneNode(true);
                    
                    // Z klonu odstraníme ovládací prvky
                    contentClone.querySelector('.jcp-icon-container')?.remove();
                    contentClone.querySelectorAll('audio, .wp-block-audio, .wp-audio-shortcode').forEach(el => el.remove());

                    let htmlContent = contentClone.innerHTML;

                    // Najdeme pozici <hr> a vezmeme jen obsah za ním
                    const hrIndex = htmlContent.indexOf('<hr');
                    if (hrIndex !== -1) {
                        htmlContent = htmlContent.substring(htmlContent.indexOf('>', hrIndex) + 1);
                    }

                    // Převedeme HTML na čistý text
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = htmlContent;
                    
                    // Získáme text a vyčistíme ho
                    let textToCopy = tempDiv.innerText.trim();
                    textToCopy = textToCopy.replace(/\s*\([^)]*\)/g, '').trim();

                    if (textToCopy) {
                        navigator.clipboard.writeText(textToCopy).then(() => {
                            const icon = this.querySelector('i');
                            icon.classList.remove('fa-clipboard');
                            icon.classList.add('fa-check');

                            setTimeout(() => {
                                icon.classList.remove('fa-check');
                                icon.classList.add('fa-clipboard');
                            }, 2000);

                        }).catch(err => {
                            console.error('Chyba při kopírování: ', err);
                        });
                    }
                });
            }
        });
        </script>
        <?php
    }
}
// Tato akce vloží skript do patičky stránky
add_action( 'wp_footer', 'jcp_add_copy_script_js' );