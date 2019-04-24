import React from 'react';
import PropsType from 'prop-types';
import DataTableComponent from 'react-data-table-component';
import {FontIcon, Checkbox } from 'react-md';

class DataTable extends React.PureComponent{
    constructor(props){
        super(props);
    }
    static propTypes = {
        title: '',
        sort: null,
        onSingleDelete: (item) => {},
        onMultipleDelete: (items) => {},
        onRowClicked: (item) => {}
    }

    render() {
        const {title, columns, values, sort, onRowClicked, onSingleDelete, onMultipleDelete } = this.props;
        return(
            <DataTableComponent
                title={title}
                columns={columns}
                data={values}
                selectableRows
                highlightOnHover
                defaultSortField={sort ? sort : columns[0].selector}
                sortIcon={<FontIcon>arrow_downward</FontIcon>}
                selectableRowsComponent={Checkbox}
                selectableRowsComponentProps={{ uncheckedIcon: isIndeterminate => (isIndeterminate ? <FontIcon>indeterminate_check_box</FontIcon>: <FontIcon>check_box_outline_blank</FontIcon>) }}
                onRowClicked={onRowClicked}
                pagination
                {...this.props}
            />
        );
    }
}
DataTable.propTypes = {
    title: PropsType.string,
    sort: PropsType.string,
    columns: PropsType.array.isRequired,
    values: PropsType.array.isRequired,
    onSingleDelete: PropsType.func,
    onMultipleDelete: PropsType.func,
    onRowClicked: PropsType.func,
}
export default DataTable;