import React from 'react';
import {Form} from 'react-bootstrap';
import PropTypes from 'prop-types';

class InputGroupReact extends React.Component{
    constructor(props){
        super(props);
    }
    static propTypes = {
        value: "",
        label: "",
        placeholder: "",
        title: "",
        error: null,
        readOnly: false,
    }
    render() {
        const {label, type, name, title, placeholder, onChange, error, value, readOnly} = this.props;
        return(
            <Form.Group>
                {label ? <Form.Label>{label}</Form.Label> : ""}
                <Form.Control
                    value={value}
                    type={type}
                    name={name}
                    title={title}
                    placeholder={placeholder}
                    onChange={onChange}
                    readOnly={readOnly}
                />
                {error ? <div className="invalid-feedback" style={{display: 'block'}}>{error}</div> : ""}

            </Form.Group>
        );
    }
}

InputGroupReact.propTypes = {
    value: PropTypes.oneOfType([
        PropTypes.string,
        PropTypes.number,
        PropTypes.bool
    ]),
    label: PropTypes.string,
    placeholder: PropTypes.string,
    title: PropTypes.string,
    name: PropTypes.string.isRequired,
    type: PropTypes.string.isRequired,
    error: PropTypes.string,
    onChange: PropTypes.func.isRequired
};

export default InputGroupReact;