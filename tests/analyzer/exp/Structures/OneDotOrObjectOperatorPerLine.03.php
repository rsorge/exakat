<?php

$expected     = array('$a4->m(2)->b->c',
                      '$a3->m(2)->b( )->c( )',
                      '$a2->m(2)->b( )->c',
                      '$a1->m(2)->b->c',
);

$expected_not = array('$f4->m(1)->b->c',
                      '$f3->m(1)->b( )->c( )',
                      '$f2->m(1)->b( )->c',
                      '$f1->m(1)->b->c',
                      "\$a4->b('a' . 'c');"
);

?>