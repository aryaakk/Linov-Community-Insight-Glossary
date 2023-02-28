<template>
    <button class="btn btn-outline-secondary" @click="exportExcel">
        <slot></slot>
    </button>
</template>

<script>
    import XLSX from 'xlsx/xlsx';

    export default {
        name: "vue-excel-xlsx",
        props: {
            columns: {
                type: Array,
                default: () => []
            },
            data: {
                type: Array,
                default: () =>  []
            },
            fileName: {
                type: String,
                default: 'excel'
            },
            sheetName: {
                type: String,
                default: 'SheetName'
            },
            fileType: {
                type: String,
                default: 'xlsx',
                validator: (val) => ['xlsx', 'xls'].includes(val)
            }
        },

        methods: {
            exportExcel() {
                let createXLSLFormatObj = [];
                let newXlsHeader = [];
                let vm = this;
                if (vm.columns.length === 0){
                    console.log("Add columns!");
                    return;
                }
                if (vm.data.length === 0){
                    console.log("Add data!");
                    return;
                }
                vm.columns.map(column => {
                    newXlsHeader.push(column.label);
                });

                createXLSLFormatObj.push(newXlsHeader);
                vm.data.map(value => {
                    let innerRowData = [];
                    vm.columns.map(val => {
                      let fieldValue = value[val.key];
                      if (this.$isNestedObject) {
                        fieldValue = this.$getNestedObject(value, val.key);
                      }
                      if (val.dataFormat && typeof val.dataFormat === 'function') {
                            innerRowData.push(val.dataFormat(fieldValue));
                        } else {
                            innerRowData.push(fieldValue);
                        }
                    });
                    createXLSLFormatObj.push(innerRowData);
                });

                let fileName = vm.fileName + "." + vm.fileType;

                let ws_name = vm.sheetName;

                let wb = XLSX.utils.book_new(),
                    ws = XLSX.utils.aoa_to_sheet(createXLSLFormatObj);
                XLSX.utils.book_append_sheet(wb, ws, ws_name);
                XLSX.writeFile(wb, fileName);
            },
        }
    }
</script>