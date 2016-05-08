/**
 * Entities Module Scripts for Phire CMS 2
 */

jax(document).ready(function(){
    if (jax('#entities-form')[0] != undefined) {
        jax('#checkall').click(function(){
            if (this.checked) {
                jax('#entities-form').checkAll(this.value);
            } else {
                jax('#entities-form').uncheckAll(this.value);
            }
        });
        jax('#entities-form').submit(function(){
            return jax('#entities-form').checkValidate('checkbox', true);
        });
    }

    if (jax('#entity-types-form')[0] != undefined) {
        jax('#checkall').click(function(){
            if (this.checked) {
                jax('#entity-types-form').checkAll(this.value);
            } else {
                jax('#entity-types-form').uncheckAll(this.value);
            }
        });
        jax('#entity-types-form').submit(function(){
            return jax('#entity-types-form').checkValidate('checkbox', true);
        });
    }
});