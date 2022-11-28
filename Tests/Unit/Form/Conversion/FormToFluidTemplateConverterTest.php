<?php
namespace FluidTYPO3\Flux\Tests\Unit\Form\Conversion;

/*
 * This file is part of the FluidTYPO3/Flux project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use FluidTYPO3\Flux\Form;
use FluidTYPO3\Flux\Form\Conversion\FormToFluidTemplateConverter;
use FluidTYPO3\Flux\Tests\Unit\AbstractTestCase;

class FormToFluidTemplateConverterTest extends AbstractTestCase
{
    public function testConvertGeneratesExpectedOutput(): void
    {
        $form = Form::create(['id' => 'test-form']);

        $sheet = $form->createContainer(Form\Container\Sheet::class, 'somefields');
        $sheet->createField(Form\Field\Input::class, 'input');
        $sheet->createField(Form\Field\Select::class, 'select');
        $sheet->createField(Form\Field\Checkbox::class, 'checkbox')->setClearable(true);

        $grid = Form\Container\Grid::create();
        $grid->setParent($form);

        $column = $grid->createContainer(Form\Container\Row::class, 'row')
            ->createContainer(Form\Container\Column::class, 'column');
        $column->setColumnPosition(3);

        $subject = new FormToFluidTemplateConverter();
        $output = $subject->convertFormAndGrid($form, $grid, []);

        $expected = <<<FLUID
<f:layout />
<f:section name="Configuration">
    <flux:form id="test-form">
        <!-- Generated by EXT:flux from runtime configured content type -->
<flux:form.sheet name="somefields" label="LLL:EXT:flux/Resources/Private/Language/locallang.xlf:flux.test-form.sheets.somefields">
<flux:field.input name="input" label="LLL:EXT:flux/Resources/Private/Language/locallang.xlf:flux.test-form.fields.input"transform=""  default="" />
<flux:field.select name="select" label="LLL:EXT:flux/Resources/Private/Language/locallang.xlf:flux.test-form.fields.select"transform=""  default="" />
<flux:field.checkbox name="checkbox" label="LLL:EXT:flux/Resources/Private/Language/locallang.xlf:flux.test-form.fields.checkbox"transform=""  default=""  clearable="1" />
</flux:form.sheet>

    </flux:form>
    <flux:grid>
        <!-- Generated by EXT:flux from runtime configured content type -->
<flux:grid.row>
<flux:grid.column name="column" label="LLL:EXT:flux/Resources/Private/Language/locallang.xlf:flux.test-form.columns.column" colPos="3" />
</flux:grid.row>

    </flux:grid>
</f:section>

<f:section name="Main">
Hello world
</f:section>
FLUID;

        self::assertSame($expected, $output);
    }
}