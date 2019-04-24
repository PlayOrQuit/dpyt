import React from 'react';
import PropsType from 'prop-types';
class Icon extends React.PureComponent{
    constructor(props) {
        super(props);
    }
    render() {
        return(
            <i className={this.props.name}></i>
        );
    }
}
Icon.propTypes = {
    name: PropsType.string.isRequired
}
export default Icon;
