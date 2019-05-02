import React from 'react';
import {Form, Image} from 'react-bootstrap';
import PropTypes from 'prop-types';
import Select, {components} from 'react-select';
const {Option} = components;
const IconOption = (props) => (
    <Option {...props}>
        <Image width={30} height={30} style={{ marginRight: 5}} src={props.data.path} roundedCircle/>
        {props.data.label}
    </Option>
);
class SelectGroupReact extends React.Component{
    constructor(props){
        super(props);
    }
    static propTypes = {
        value: "",
        label: "",
        title: "",
        placeholder: "",
        error: null,
        image: false,

    }
    render() {
        const {label, image, name, error, value, options, onChange} = this.props;
        console.log(options);
        return(
            <Form.Group>
                {label ? <Form.Label>{label}</Form.Label> : ""}
                <Select
                    name={name}
                    options={options}
                    value={value}
                    onChange={onChange}
                    components={image ? { Option: IconOption } : null}
                />
                {error ? <div className="invalid-feedback">{error}</div> : ""}
            </Form.Group>
        );
    }
}
SelectGroupReact.propTypes = {
    value: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.number,
        PropTypes.bool
    ]),
    label: PropTypes.string,
    title: PropTypes.string,
    image: PropTypes.bool,
    options: PropTypes.arrayOf( PropTypes.shape({
        value: PropTypes.oneOfType([
            PropTypes.string,
            PropTypes.number,
            PropTypes.bool
        ]).isRequired,
        label: PropTypes.string.isRequired,
        path: PropTypes.string
    })).isRequired,
    name: PropTypes.string.isRequired,
    error: PropTypes.string,
    onChange: PropTypes.func.isRequired
};
export default SelectGroupReact;