import React from "react";
import PropTypes from "prop-types";

const defaultButton = props => <button {...props}>{props.children}</button>;

class Pagination extends React.Component {

    constructor(props) {
        super(props);
        this.changePage = this.changePage.bind(this);
        this.state = {
            visiblePages: this.getVisiblePages(null, props.pages)
        };
    }

    static propTypes = {
        pages: PropTypes.number,
        page: PropTypes.number,
        PageButtonComponent: PropTypes.any,
        onPageChange: PropTypes.func,
        previousText: PropTypes.string,
        nextText: PropTypes.string
    };

    componentWillReceiveProps(nextProps) {
        if (this.props.pages !== nextProps.pages) {
            this.setState({
                visiblePages: this.getVisiblePages(null, nextProps.pages)
            });
        }

        this.changePage(nextProps.page + 1);
    }

    filterPages = (visiblePages, totalPages) => {
        return visiblePages.filter(page => page <= totalPages);
    };

    getVisiblePages = (page, total) => {
        if (total < 7) {
            return this.filterPages([1, 2, 3, 4, 5, 6], total);
        } else {
            if (page % 5 >= 0 && page > 4 && page + 2 < total) {
                return [1, page - 1, page, page + 1, total];
            } else if (page % 5 >= 0 && page > 4 && page + 2 >= total) {
                return [1, total - 3, total - 2, total - 1, total];
            } else {
                return [1, 2, 3, 4, 5, total];
            }
        }
    };

    changePage(page) {
        const activePage = this.props.page + 1;

        if (page === activePage) {
            return;
        }

        const visiblePages = this.getVisiblePages(page, this.props.pages);

        this.setState({
            visiblePages: this.filterPages(visiblePages, this.props.pages)
        });

        this.props.onPageChange(page - 1);
    }

    render() {
        const {PageButtonComponent = defaultButton} = this.props;
        const {visiblePages} = this.state;
        const activePage = this.props.page + 1;

        return (
            <ul className="pagination justify-content-center mt-5">
                <li className={ activePage === 1 ? "page-item page-prev disabled" : "page-item page-prev"}>
                    <PageButtonComponent
                        className="page-link"
                        onClick={() => {
                            if (activePage === 1) return;
                            this.changePage(activePage - 1);
                        }}
                    >
                        <span aria-hidden="true">&laquo;</span>
                        <span className="sr-only">Previous</span>
                    </PageButtonComponent>
                </li>
                {visiblePages.map((page, index, array) => {
                    return (
                        <li className={
                            activePage === page
                                    ? "page-item active"
                                : "page-item"
                        }>
                            <PageButtonComponent
                                key={page}
                                className="page-link"
                                onClick={this.changePage.bind(null, page)}
                            >
                                {array[index - 1] + 2 < page ? `...${page}` : page}
                            </PageButtonComponent>
                        </li>
                    );
                })}
                <li className={activePage === this.props.pages ? "page-item page-next disabled" : "page-item page-next"}>
                    <PageButtonComponent
                        className="page-link"
                        onClick={() => {
                            if (activePage === this.props.pages) return;
                            this.changePage(activePage + 1);
                        }}
                    >
                        <span aria-hidden="true">&raquo;</span>
                        <span className="sr-only">Next</span>
                    </PageButtonComponent>
                </li>
            </ul>
        );
    }
}
export default Pagination;
